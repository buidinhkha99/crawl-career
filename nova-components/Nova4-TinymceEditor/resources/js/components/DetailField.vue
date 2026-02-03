<template>
  <PanelItem :index="index" :field="field">

    <template #value>
        <div v-if="field.shouldShow && hasContent" class="break-normal">
            <div
                v-if="expanded"
                class="prose prose-sm dark:prose-invert"
                :class="{ 'whitespace-pre-wrap': field.plainText }"
                v-html="field.value"
            />
            <button
                type="button"
                @click="toggle"
                class="link-default"
                :class="{ 'mt-6': expanded }"
                aria-role="button"
                tabindex="0"
            >
                {{ showHideLabel }}
            </button>
        </div>
        <div v-else-if="hasContent" class="break-normal">
            <div
                v-if="expanded"
                class="prose prose-sm dark:prose-invert"
                :class="{ 'whitespace-pre-wrap': field.plainText }"
                v-html="field.value"
            />

            <button
                type="button"
                v-if="!field.shouldShow"
                @click="toggle"
                class="link-default"
                :class="{ 'mt-6': expanded }"
                aria-role="button"
                tabindex="0"
            >
                {{ showHideLabel }}
            </button>
        </div>
        <div v-else>&mdash;</div>
    </template>
    </PanelItem>
</template>

<script>
export default {
  props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],

  data() {
    return {
      content: 'Hidden content', expanded: this.field?.shouldShow,
    }
  },

    methods: {
        toggle() {
            this.expanded = !this.expanded
        },
    },

    computed: {
        hasContent() {
            return this.content !== '' && this.content !== null
        },

        showHideLabel() {
            return !this.expanded ? this.__('Show Content') : this.__('Hide Content')
        },
    },
}
</script>
