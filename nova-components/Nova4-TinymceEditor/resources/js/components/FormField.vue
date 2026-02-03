<template>
  <DefaultField :field="field" :errors="errors" :show-help-text="showHelpText" :full-width-content="fullWidthContent">
    <template #field>
      <div v-if="showForm">
        <editor
          :id="field.attribute"
          :cloud-channel="6"
          v-model="value"
          :api-key="field.options.apiKey"
          :init="field.options.init"
          :plugins="field.options.plugins"
          :toolbar="field.options.toolbar"
          :placeholder="field.name"
        />
      </div>
        <button
          type="button"
          @click="toggle"
          class="link-default"
          :class="{ 'mt-6': showForm }"
          aria-role="button"
          tabindex="0"
        >
          {{ showHideLabel }}
        </button>
    </template>
  </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import Editor from "@tinymce/tinymce-vue";

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field", "options"],

  components: {
    editor: Editor,
  },

  data() {
    return {
        showForm: !!(this.field?.showForm === undefined || this.field.showForm),
    }
  },

  computed: {
    showHideLabel() {
        return !this.showForm ? this.__('Show Content') : this.__('Hide Content')
    },
  },

  created() {
    this.setEditorTheme();

      if (this.field.options.init.file_picker_callback === true) {
        this.field.options.init.file_picker_callback = function (cb, value, meta) {
          var input = document.createElement('input');
          input.setAttribute('type', 'file');
          input.setAttribute('accept', 'image/*');

          /*
            Note: In modern browsers input[type="file"] is functional without
            even adding it to the DOM, but that might not be the case in some older
            or quirky browsers like IE, so you might want to add it to the DOM
            just in case, and visually hide it. And do not forget do remove it
            once you do not need it anymore.
          */

          input.onchange = function () {
            var file = this.files[0];

            var reader = new FileReader();
            reader.onload = function () {
                /*
                  Note: Now we need to register the blob in TinyMCEs image blob
                  registry. In the next release this part hopefully won't be
                  necessary, as we are looking to handle it internally.
                */
                var id = 'blobid' + (new Date()).getTime();
                var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
            };
            reader.readAsDataURL(file);
          };

          input.click();
        };
      }
  },

  methods: {
      toggle() {
          this.showForm = !this.showForm
      },

    setEditorTheme() {
        const selectedNovaTheme = localStorage.novaTheme;

        if (typeof selectedNovaTheme !== 'undefined') {
            if (selectedNovaTheme == 'system') {
                this.setSystemMode();
            } else if (selectedNovaTheme == 'dark') {
                this.field.options.init.skin = 'oxide-dark';
                this.field.options.init.content_css = 'dark';
            } else {
                this.field.options.init.skin = 'oxide';
                this.field.options.init.content_css = 'default';
            }
        } else {
            this.setSystemMode();
        }
    },

    setSystemMode() {
      this.field.options.init.skin =
        window.matchMedia("(prefers-color-scheme: dark)").matches ||
        document.querySelector("html").classList.contains("dark")
          ? "oxide-dark"
          : "oxide";
      this.field.options.init.content_css =
        window.matchMedia("(prefers-color-scheme: dark)").matches ||
        document.querySelector("html").classList.contains("dark")
          ? "dark"
          : "default";
    },
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || "";
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || "");
    },
  },
};
</script>
