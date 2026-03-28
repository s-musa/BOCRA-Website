<template>
<!--   <div class="main-container">-->
<!--      <div class="editor-container editor-container_classic-editor editor-container_include-block-toolbar" ref="editorContainerElement">-->
<!--         <div class="editor-container__editor">-->
<!--            <div ref="editorElement">-->
<!--               <ckeditor v-if="editor && config" :editor="editor"-->
<!--                         :config="config"-->
<!--                         :model-value="props.modelValue"-->
<!--                         @update:model-value="(value) => emit('update:modelValue', value)" />-->
<!--            </div>-->
<!--         </div>-->
<!--      </div>-->
<!--   </div>-->
</template>

<script setup>
import { computed, ref, onMounted, useTemplateRef } from 'vue';
import { Ckeditor, useCKEditorCloud } from '@ckeditor/ckeditor5-vue';

// const LICENSE_KEY =
//    'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3Nzg2MzAzOTksImp0aSI6ImE2Y2U4NWI5LTcyM2QtNGU5MS04Y2E5LWQxZDBhYWUwMTllNSIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIiwiQk9YIl0sInZjIjoiOTgwZGJjMzMifQ.dzRv3nO6DMj9rczpOLHa-Yj9rpSc0KgC35_Nf2W1gR0cpp-av4sMXiJSaiEQ2w6UCePAK1iwuiy-ApBJlEuD9g';
//
// const editorMenuBar = useTemplateRef('editorMenuBarElement');
//
// const cloud = useCKEditorCloud({ version: '45.1.0' });
//
// const isLayoutReady = ref(false);
//
// const editor = computed(() => {
//    if (!cloud.data.value) {
//       return null;
//    }
//
//    return cloud.data.value.CKEditor.ClassicEditor;
// });
// const config = computed(() => {
//    if (!isLayoutReady.value) {
//       return null;
//    }
//
//    if (!cloud.data.value) {
//       return null;
//    }
//
//    const {
//       Alignment,
//       AutoLink,
//       Autosave,
//       BlockQuote,
//       BlockToolbar,
//       Bold,
//       Bookmark,
//       Code,
//       CodeBlock,
//       Essentials,
//       FontBackgroundColor,
//       FontColor,
//       FontFamily,
//       FontSize,
//       Heading,
//       Highlight,
//       HorizontalLine,
//       Indent,
//       IndentBlock,
//       Italic,
//       Link,
//       Paragraph,
//       RemoveFormat,
//       Strikethrough,
//       Subscript,
//       Superscript,
//       Table,
//       TableCaption,
//       TableCellProperties,
//       TableColumnResize,
//       TableProperties,
//       TableToolbar,
//       Underline
//    } = cloud.data.value.CKEditor;
//
//    return {
//       toolbar: {
//          items: [
//             'undo',
//             'redo',
//             '|',
//             'heading',
//             '|',
//             'fontSize',
//             'fontFamily',
//             'fontColor',
//             'fontBackgroundColor',
//             '|',
//             'bold',
//             'italic',
//             'underline',
//             '|',
//             'link',
//             'insertTable',
//             'highlight',
//             'blockQuote',
//             'codeBlock',
//             '|',
//             'alignment',
//             '|',
//             'outdent',
//             'indent'
//          ],
//          shouldNotGroupWhenFull: false
//       },
//       plugins: [
//          Alignment,
//          AutoLink,
//          Autosave,
//          BlockQuote,
//          BlockToolbar,
//          Bold,
//          Bookmark,
//          Code,
//          CodeBlock,
//          Essentials,
//          FontBackgroundColor,
//          FontColor,
//          FontFamily,
//          FontSize,
//          Heading,
//          Highlight,
//          HorizontalLine,
//          Indent,
//          IndentBlock,
//          Italic,
//          Link,
//          Paragraph,
//          RemoveFormat,
//          Strikethrough,
//          Subscript,
//          Superscript,
//          Table,
//          TableCaption,
//          TableCellProperties,
//          TableColumnResize,
//          TableProperties,
//          TableToolbar,
//          Underline
//       ],
//       blockToolbar: [
//          'fontSize',
//          'fontColor',
//          'fontBackgroundColor',
//          '|',
//          'bold',
//          'italic',
//          '|',
//          'link',
//          'insertTable',
//          '|',
//          'outdent',
//          'indent'
//       ],
//       fontFamily: {
//          supportAllValues: true
//       },
//       fontSize: {
//          options: [10, 12, 14, 'default', 18, 20, 22],
//          supportAllValues: true
//       },
//       heading: {
//          options: [
//             {
//                model: 'paragraph',
//                title: 'Paragraph',
//                class: 'ck-heading_paragraph'
//             },
//             {
//                model: 'heading1',
//                view: 'h1',
//                title: 'Heading 1',
//                class: 'ck-heading_heading1'
//             },
//             {
//                model: 'heading2',
//                view: 'h2',
//                title: 'Heading 2',
//                class: 'ck-heading_heading2'
//             },
//             {
//                model: 'heading3',
//                view: 'h3',
//                title: 'Heading 3',
//                class: 'ck-heading_heading3'
//             },
//             {
//                model: 'heading4',
//                view: 'h4',
//                title: 'Heading 4',
//                class: 'ck-heading_heading4'
//             },
//             {
//                model: 'heading5',
//                view: 'h5',
//                title: 'Heading 5',
//                class: 'ck-heading_heading5'
//             },
//             {
//                model: 'heading6',
//                view: 'h6',
//                title: 'Heading 6',
//                class: 'ck-heading_heading6'
//             }
//          ]
//       },
//       licenseKey: LICENSE_KEY,
//       link: {
//          addTargetToExternalLinks: true,
//          defaultProtocol: 'https://',
//          decorators: {
//             toggleDownloadable: {
//                mode: 'manual',
//                label: 'Downloadable',
//                attributes: {
//                   download: 'file'
//                }
//             }
//          }
//       },
//       menuBar: {
//          isVisible: true
//       },
//       placeholder: 'Type or paste your content here!',
//       table: {
//          contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
//       }
//    };
// });
// const props = defineProps({
//    modelValue: String
// });
// const emit = defineEmits(['update:modelValue']);
//
// onMounted(() => {
//    isLayoutReady.value = true;
// });
</script>

<style scoped>
@import url("resources/scss/editor-fonts.scss");

@media print {
   body {
      margin: 0 !important;
   }
}
.main-container {
   font-family: 'Lato';
   width: fit-content;
   margin-left: auto;
   margin-right: auto;
}
.ck-content {
   font-family: 'Lato';
   line-height: 1.6;
   word-break: break-word;
}
.editor-container_classic-editor .editor-container__editor {
   min-width: 795px;
}
.editor-container_include-block-toolbar {
   padding-top: 20px;
   padding-bottom: 20px;
}
</style>
