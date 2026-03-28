<template>
   <Vue3Dropzone
      ref="dz"
      v-model="files"
      v-model:previews="previewUrls"
      :mode="mode"
      :multiple="multiple"
      :accept="accept"
      :max-files="maxFiles"
      :max-file-size="maxFileSize"
      :preview-position="previewPosition"
      :show-select-button="showSelectButton"
      imgWidth="120px"
      imgHeight="120px"
      :server-side="!!uploadUrl"
      :upload-endpoint="uploadUrl || undefined"
      :delete-endpoint="deleteUrl || undefined"
      :headers="computedHeaders"
      @update:modelValue="onFilesUpdate"
      @fileUploaded="onFileUploaded"
      @fileRemoved="onFileRemoved"
      @previewRemoved="onPreviewRemoved"
      @error="onError"
   />
</template>

<script setup>
import {ref, computed, watch, onBeforeUnmount} from "vue";
import axios from "axios";
import Vue3Dropzone from "@jaxtheprime/vue3-dropzone";
import "@jaxtheprime/vue3-dropzone/dist/style.css";

/**
 * Props
 * - If uploadUrl is provided => files upload immediately (silent; no Inertia visit).
 * - If uploadUrl is NOT provided => we emit raw files via `files-added` so the parent can append them to a form.
 * - For deleting existing media, pass `existing` as [{ id, url }, ...] and a `deleteUrl`.
 */
const props = defineProps({
   // Behavior
   multiple: {type: Boolean, default: false},       // bulk upload toggle
   mode: {type: String, default: "edit"},           // 'drop' | 'preview' | 'edit'
   previewPosition: {type: String, default: "inside"}, // 'inside' | 'outside'
   showSelectButton: {type: Boolean, default: false},
   
   // Upload / delete endpoints
   uploadUrl: {type: String, default: null},
   deleteUrl: {type: String, default: null},
   
   // Existing previews (each with an ID for delete mapping)
   existing: {
      type: Array,
      default: () => [], // [{ id: 123, url: 'https://...' }]
   },
   
   // Validation
   accept: {type: String, default: undefined}, // e.g., "image/*,application/pdf"
   maxFiles: {type: Number, default: 5},
   maxFileSize: {type: Number, default: 5}, // MB (library prop)
   
   // Headers/CSRF
   headers: {type: Object, default: () => ({})},
   csrfToken: {type: String, default: null},
   
   /**
    * Optional: how to extract the uploaded media ID from your API response.
    * Default tries common shapes.
    */
   extractId: {
      type: Function,
      default: (res) =>
         res?.id ?? res?.data?.id ?? res?.media?.id ?? res?.result?.id ?? null,
   },
});

const emit = defineEmits([
   "files-added", // emitted in form mode (no uploadUrl)
   "preview-removed", // emitted in form mode (no uploadUrl)
   "success",     // { file, response }
   "error",       // { file?, error }
   "deleted",     // { id, url } after successful delete
]);

// v-model bindings for the dropzone
const files = ref([]);                    // new File objects
const previewUrls = ref(props.existing.map(e => e.url)); // existing preview URLs

// For tracking "newly added" files when in form mode
const seen = new WeakSet();

// Map uploaded File -> server id (populated from `fileUploaded` responses)
const uploadedIdByFile = new WeakMap();

// Map preview URL -> id from `existing`
const idByUrl = computed(() => {
   const m = new Map();
   props.existing.forEach(({id, url}) => m.set(url, id));
   return m;
});

// Compose headers (add CSRF + X-Requested-With by default to keep it "silent")
const computedHeaders = computed(() => {
   const base = {
      "X-Requested-With": "XMLHttpRequest",
   };
   const token =
      props.csrfToken ||
      (typeof document !== "undefined"
         ? document.querySelector('meta[name="csrf-token"]')?.getAttribute("content")
         : null);
   if (token) base["X-CSRF-TOKEN"] = token;
   return {...base, ...props.headers};
});

// === Events from the library ===
// When files array changes (user dropped/selected files)
function onFilesUpdate(newFiles) {
   if (!props.uploadUrl) {
      // Form mode: emit ONLY newly added File objects
      const added = newFiles.filter((f) => !seen.has(f));
      added.forEach((f) => seen.add(f));
      if (added.length) emit("files-added", added);
   }
}

// When a file finishes uploading (server-side mode)
function onFileUploaded({file, response}) {
   try {
      const id = props.extractId(response);
      if (id != null) uploadedIdByFile.set(file, id);
      emit("success", {file, response});
   } catch (e) {
      emit("error", {file, error: e});
   }
}

// When a new File (not a preview URL) is removed from the UI
async function onFileRemoved(file) {
   // Only attempt server delete if we uploaded it and have a deleteUrl
   console.log(props.deleteUrl);
   if (!props.deleteUrl) return;
   
   const id = uploadedIdByFile.get(file);
   
   console.log("media id: ", id);
   if (id == null) return; // nothing to delete server-side (e.g., form mode)
   try {
      await axios.delete(props.deleteUrl, {
         data: { id }, // must be wrapped
         headers: computedHeaders.value,
      });
      emit("deleted", {id, url: null});
   } catch (error) {
      emit("error", {file, error});
   }
}

// When an existing preview URL is removed from the UI
async function onPreviewRemoved(previewObj) {
   console.log("Dropzone onPreviewRemoved:", previewObj);
   
   // ALWAYS emit to parent first
   emit("preview-removed", previewObj);
   
   // Only handle deletion here if deleteUrl is provided AND we have an ID
   // if (props.deleteUrl && id != null) {
   //    try {
   //       await axios.delete(props.deleteUrl.replace(':id', id), {
   //          headers: computedHeaders.value,
   //       });
   //       emit("deleted", {id, url});
   //    } catch (error) {
   //       emit("error", {error});
   //    }
   // }
}

function onError(payload) {
   emit("error", payload);
}

// Keep previewUrls in sync if `existing` changes from the parent
watch(
   () => props.existing,
   (arr) => {
      previewUrls.value = arr.map((e) => e.url);
   },
   {deep: true}
);

// Clean up any object URLs if you ever add custom previews outside the lib
onBeforeUnmount(() => {
});
</script>

<style scoped>
.global-dropzone {
   display: flex;
   flex-direction: column;
   gap: 0.75rem;
}
</style>
