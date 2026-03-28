<template>
   <div v-if="editor" class="container mt-2">
      <div class="control-group">
         <div class="button-group">
            <bubble-menu :editor="editor">
               <div class="bubble-menu">
                  <button @click="editor.chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
                     <i class="bx bx-bold" />
                  </button>
                  <button @click="editor.chain().focus().toggleItalic().run()" :class="{ 'is-active': editor.isActive('italic') }">
                     <i class="bx bx-italic" />
                  </button>
                  <button @click="editor.chain().focus().toggleUnderline().run()" :class="{ 'is-active': editor.isActive('underline') }">
                     <i class="bx bx-underline" />
                  </button>
                  <button @click="editor.chain().focus().toggleStrike().run()" :class="{ 'is-active': editor.isActive('strike') }">
                     <i class="bx bx-strikethrough" />
                  </button>
               </div>
            </bubble-menu>
            <button v-for="tool in toolBarItems"
                    :key="tool.title"
                    :title="tool.title"
                    :class="{ 'is-active': editor.isActive(tool.name) }"
                    @click.prevent="applyTool(tool)"
            >
               <i v-if="tool.icon" :class="'bx ' + tool.icon"></i>
               <span v-else>{{ tool.title }}</span>
            </button>
            <input
               type="color"
               @input="editor.chain().focus().setColor($event.target.value).run()"
               :value="editor.getAttributes('textStyle').color"
            />
            <button class="btn btn-light" data-bs-toggle="dropdown">
               <i class="bx bx-table"></i>
            </button>
            <ul class="dropdown-menu">
               <li><a class="dropdown-item" @click.prevent="editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()">Insert table</a></li>
               <li><a class="dropdown-item" @click.prevent="editor.chain().focus().addRowAfter().run()">Add row</a></li>
               <li><a class="dropdown-item" @click.prevent="editor.chain().focus().addColumnAfter().run()">Add column</a></li>
               <li><a class="dropdown-item" @click.prevent="editor.chain().focus().deleteRow().run()">Delete row</a></li>
               <li><a class="dropdown-item" @click.prevent="editor.chain().focus().deleteColumn().run()">Delete column</a></li>
               <li><a class="dropdown-item" @click.prevent="editor.chain().focus().deleteTable().run()">Delete table</a></li>
               <li><a class="dropdown-item" @click.prevent="editor.chain().focus().toggleHeaderRow().run()">Toggle header row</a></li>
            </ul>
            <button
               @click="editor.chain().focus().setTextAlign('left').run()"
               :class="{ 'is-active': editor.isActive({ textAlign: 'left' }) }"
            >
               <i class="bx bx-align-left"></i>
            </button>
            
            <button
               @click="editor.chain().focus().setTextAlign('center').run()"
               :class="{ 'is-active': editor.isActive({ textAlign: 'center' }) }"
            >
               <i class="bx bx-align-middle"></i>
            </button>
            
            <button
               @click="editor.chain().focus().setTextAlign('right').run()"
               :class="{ 'is-active': editor.isActive({ textAlign: 'right' }) }"
            >
               <i class="bx bx-align-right"></i>
            </button>
            
            <button
               @click="editor.chain().focus().setTextAlign('justify').run()"
               :class="{ 'is-active': editor.isActive({ textAlign: 'justify' }) }"
            >
               <i class="bx bx-align-justify"></i>
            </button>
         </div>
      </div>
      <editor-content :editor="editor" />
   </div>
</template>

<script>
import StarterKit from '@tiptap/starter-kit'
import {Color, TextStyle} from '@tiptap/extension-text-style'
import { ListItem } from '@tiptap/extension-list'
import Underline from '@tiptap/extension-underline';
import { Editor, EditorContent } from '@tiptap/vue-3'
import { BubbleMenu } from '@tiptap/vue-3/menus'
import { TableKit } from '@tiptap/extension-table'
import TextAlign from '@tiptap/extension-text-align'

export default {
   components: {
      EditorContent,
      BubbleMenu
   },
   props: {
      modelValue: {
         type: String,
         default: '',
      },
   },
   data() {
      return {
         editor: null,
         extensions: [
            StarterKit,
         ],
         toolBarItems: [
            {
               name: "undo", icon: "bx-undo", title: "Undo", command: ed => ed.chain().focus().undo().run(),
               can: ed => ed.can().chain().focus().undo().run()
            },
            {
               name: "redo", icon: "bx-redo", title: "Redo", command: ed => ed.chain().focus().redo().run(),
               can: ed => ed.can().chain().focus().redo().run()
            },
            { name: "bold", icon: "bx-bold", title: "Bold", command: ed => ed.chain().focus().toggleBold().run(),
               can: ed => ed.can().chain().focus().toggleBold().run(), isActive: ed => ed.isActive('bold'),
            },
            {
               name: "italic", icon: "bx-italic", title: "Italic", command: ed => ed.chain().focus().toggleItalic().run(),
               can: ed => ed.can().chain().focus().toggleItalic().run(), isActive: ed => ed.isActive('italic'),
            },
            {
               name: "underline", icon: "bx-underline", title: "Underline", command: ed => ed.chain().focus().toggleUnderline().run(),
               can: ed => ed.can().chain().focus().redo().run(), isActive: ed => ed.isActive('underline'),
            },
            {
               name: "strike", icon: "bx-strikethrough", title: "Strikethrough", command: ed => ed.chain().focus().toggleStrike().run(),
               can: ed => ed.can().chain().focus().toggleStrike().run(), isActive: ed => ed.isActive('strike')
            },
            // {
            //    name: "link", icon: "bx-link", title: "Insert Link", command: ed => ed.chain().focus().toggleLink().run(),
            //    can: ed => ed.can().chain().focus().redo().run(), isActive: ed => ed.isActive('link')
            // },
            {
               name: "code", icon: "bx-code-alt", title: "Code", command: ed => ed.chain().focus().toggleCode().run(),
               can: ed => ed.can().chain().focus().toggleBlockquote().run(), isActive: ed => ed.isActive('code'),
            },
            {
               name: "blockquote", icon: "bxs-quote-right", title: "Blockquote", command: ed => ed.chain().focus().toggleBlockquote().run(),
               can: ed => ed.can().chain().focus().toggleBlockquote().run(), isActive: ed => ed.isActive('blockquote'),
            },
            {
               name: "codeBlock", icon: "bx-code-block", title: "Code Block", command: ed => ed.chain().focus().toggleCodeBlock().run(),
               can: ed => ed.can().chain().focus().toggleCodeBlock().run(), isActive: ed => ed.isActive('codeBlock'),
            },
            {
               name: "bulletList", icon: "bx-list-ul", title: "Bullet List", command: ed => ed.chain().focus().toggleBulletList().run(),
               can: ed => ed.can().chain().focus().toggleBulletList().run(), isActive: ed => ed.isActive('bulletList'),
            },
            {
               name: "orderList", icon: "bx-list-ol", title: "Ordered List", command: ed => ed.chain().focus().toggleOrderedList().run(),
               can: ed => ed.can().chain().focus().toggleOrderedList().run(), isActive: ed => ed.isActive('orderedList'),
            },
            {
               name: "paragraph", icon: "bx-paragraph", title: "Paragraph", command: ed => ed.chain().focus().setParagraph().run(),
               can: ed => ed.can().chain().focus().setParagraph().run(), isActive: ed => ed.isActive('paragraph'),
            },
            ...[1,2,3,4,5,6].map(heading => ({
               name: `h${heading}`,
               title: `H${heading}`,
               icon: null,
               command: ed => ed.chain().focus().toggleHeading({ level: heading }).run(),
               can:     ed => ed.can().chain().focus().toggleHeading({ level: heading }),
               isActive: ed => ed.isActive('heading', { level: heading }),
            })),
            {
               name: "horizontalRule", icon: "", title: "Horizontal Rule",
               command: ed => ed.chain().focus().setHorizontalRule().run(),
            },
            {
               name: "hardBreak", icon: "", title: "Hard Break",
               command: ed => ed.chain().focus().setHardBreak().run(),
            },
         ],
         activeTool: null,
      }
   },
   watch: {
      modelValue(newVal) {
         if (this.editor && newVal !== this.editor.getHTML()) {
            this.editor.commands.setContent(newVal)
         }
      },
   },
   mounted() {
      this.editor = new Editor({
         extensions: [
            Color.configure({ types: [TextStyle.name, ListItem.name] }),
            TextStyle.configure({ types: [ListItem.name] }),
            StarterKit,
            TableKit.configure({
               table: { resizable: true },
            }),
            TextAlign.configure({
               types: ['heading', 'paragraph', 'list'],
            }),
         ],
         content: this.modelValue,
         injectCSS: true,
         onUpdate: ({ editor }) => {
            this.$emit('update:modelValue', editor.getHTML())
         },
      })
   },
   beforeUnmount() {
      this.editor.destroy()
   },
   methods: {
      applyTool(tool) {
         if (!this.editor) return
         if (tool.can && !tool.can(this.editor)) return
         tool.command(this.editor)
      },
   },
}
</script>

<style scoped lang="scss">
#root {
   --white: #fff;
   --black: #2e2b29;
   --black-contrast: #110F0E;
   --gray-1: rgba(61, 37, 20, .05);
   --gray-2: rgba(61, 37, 20, .08);
   --gray-3: rgba(61, 37, 20, .12);
   --purple: #6A00F5;
   --purple-contrast: #5800cc;
   --purple-light: rgba(88, 5, 255, .05);
}
.container.editor-control {
   padding: 0 !important;
   border: 1px solid rgb(206.38, 209.46, 212.54) !important;
   border-radius: 10px;
}
.control-group {
   align-items: flex-start;
   background-color: #fff;
   display: flex;
   flex-direction: column;
   gap: 1rem;
   padding: .35rem;
   border-bottom: 1px solid rgb(206.38, 209.46, 212.54) !important;
   border-top-left-radius: 10px;
   border-top-right-radius: 10px;
}
.button-group {
   display: flex;
   flex-wrap: wrap;
   gap: .25rem;
}
.button-group > button {
   background: rgba(61, 37, 20, 0.08);
   border-radius: .5rem;
   border: none;
   color: var(--black);
   font-family: inherit;
   font-size: .875rem;
   font-weight: 500;
   line-height: 1.15;
   margin: 0;
   padding: .375rem .625rem;
   transition: all .2s cubic-bezier(.65,.05,.36,1);
}
.button-group > button.is-active {
   background: var(--bs-primary);
   color: #fff;
}
::v-deep(.tiptap) {
   margin: 0.8rem;
   min-height: 50px;
   
   :first-child {
      margin-top: 0;
   }
   ul, ol {
      padding: 0 1rem;
      margin: 1.25rem 1rem 1.25rem 0.4rem;
      
      li p {
         margin-top: 0.25em;
         margin-bottom: 0.25em;
      }
   }
   h1, h2, h3, h4, h5, h6 {
      line-height: 1.4;
      margin-top: 1.5rem;
      text-wrap: pretty;
      font-weight: 700;
   }
   h1, .h1 {
      font-size: 36px !important;
   }
   h2, .h2 {
      font-size: 32px !important;
   }
   h3, .h3 {
      font-size: 28px !important;
   }
   h4, .h4 {
      font-size: 20px !important;
   }
   h5, .h5 {
      font-size: 18px !important;
   }
   h6, .h6 {
      font-size: 15px !important;
   }
   p {
      font-size: 1rem;
   }
   blockquote {
      border-left: 3px solid rgba(61, 37, 20, .12) !important;
      margin: 1.5rem 0 !important;
      padding-left: 1rem !important;
   }
   
   code {
      background-color: rgba(61, 37, 20, .12) !important;
      border-radius: 0.4rem;
      color: var(--black) !important;
      font-size: 0.85rem;
      padding: 0.25em 0.3em;
   }
   
   pre {
      background: rgba(61, 37, 20, .12);
      border-radius: 0.5rem;
      font-family: 'JetBrainsMono', monospace;
      margin: 1.5rem 0;
      padding: 0.75rem 1rem;
      
      code {
         background: transparent !important;
         color: inherit;
         font-size: 0.8rem;
         padding: 0;
      }
   }
   hr {
      border: none;
      border-top: 1px solid rgba(61, 37, 20, .12);
      margin: 2rem 0;
   }
   &:focus {
      outline: none;
   }
   /* Bubble menu */
   .bubble-menu {
      background-color: rgba(61, 37, 20, .12);
      border: 1px solid rgba(61, 37, 20, .25);
      border-radius: 0.5rem !important;
      box-shadow: var(--shadow);
      display: flex;
      padding: 0.3rem;
      
      button {
         background-color: unset;
         
         &:hover {
            background-color: var(--gray-3);
         }
         
         &.is-active {
            background-color: var(--purple);
            
            &:hover {
               background-color: var(--purple-contrast);
            }
         }
      }
   }
   .tableWrapper {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      margin: 1rem 0;
   }
   /* main table reset inside editor */
   table {
      width: 100%;
      border-collapse: collapse;
      border-spacing: 0;
      table-layout: auto; /* if you prefer fixed widths use fixed */
      background: transparent;
      color: inherit;
      font-size: 0.95rem;
      line-height: 1.4;
      margin: 0.5rem 0;
      min-width: 300px; /* helps when many columns */
   }
   /* column group min widths (respect existing inline styles) */
   col {
      vertical-align: top;
   }
   thead, tbody {
      /* keep structure */
   }
   th, td {
      border: 1px solid rgba(61, 37, 20, 0.08); /* light border so table is visible */
      padding: 0.6rem 0.8rem;
      vertical-align: middle;
      text-align: left;
      background-clip: padding-box;
      min-width: 40px;
      word-break: break-word;
   }
   th {
      font-weight: 600;
      background: rgba(61, 37, 20, 0.04);
      color: var(--black);
   }
   tbody tr:nth-child(odd) td {
      background: rgba(0,0,0,0.01);
   }
   tr th {
      background: rgba(61, 37, 20, 0.06);
   }
   tr td p {
      margin: 0 !important;
   }
   .is-selected, td:focus, th:focus {
      outline: 2px solid rgba(99, 102, 241, 0.2);
      box-shadow: inset 0 0 0 2px rgba(99, 102, 241, 0.06);
   }
   /* small screens: allow horizontal scrolling and keep cells readable */
   @media (max-width: 768px) {
      .tableWrapper {
         margin-left: -0.5rem;
         margin-right: -0.5rem;
         padding-left: 0.5rem;
         padding-right: 0.5rem;
      }
      table {
         font-size: 0.9rem;
      }
      th, td {
         padding: 0.5rem;
      }
   }
   .tableWrapper .tableWrapper {
      overflow: visible; /* inner wrappers shouldn't create their own scrollbars */
   }
   .ProseMirror-trailingBreak {
      display: inline-block;
      height: 0;
   }
}
button.is-active,
input.is-active,
select.is-active,
textarea.is-active {
   background-color: #5800cc;
   color: #fff;
}
button:hover,
input:hover,
select:hover,
textarea:hover {
   color: #fff;
   background-color: #6A00F5 !important;
}
button:not([disabled]),
select:not([disabled]) {
   cursor: pointer
}
</style>
