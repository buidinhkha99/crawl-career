<template>
  <DefaultField :field="currentField" :errors="errors" :show-help-text="showHelpText">
    <template #field>
      <div class="o1-flex" v-if="hasValue">
        <div class="o1-flex">
          <template v-if="currentField.multiple">
            <Draggable v-model="value" item-key="id" class="o1-flex o1-flex-wrap">
              <template #item="{ element: mediaItem }">
                <MediaItem
                  :key="mediaItem.id"
                  :mediaItem="mediaItem"
                  :size="24"
                  class="o1-mb-4 o1-mr-4"
                  @contextmenu.stop.prevent="openContextMenu($event, mediaItem)"
                />
              </template>
            </Draggable>
          </template>

          <MediaItem
            v-else-if="!!value"
            class="o1-mb-4"
            :mediaItem="value"
            :size="36"
            @contextmenu.stop.prevent="openContextMenu($event, value)"
          />
        </div>
      </div>

      <LoadingButton type="button" @click.prevent.stop="openChooseModal">Choose media</LoadingButton>

      <MediaItemContextMenu
        :id="`form-media-hub-field-ctx-menu-${currentField.attribute}`"
        :showEvent="ctxShowEvent"
        :options="ctxOptions"
        @close="ctxShowEvent = void 0"
        :mediaItem="ctxMediaItem"
      />

      <ChooseMediaModal
        :field="currentField"
        :initialSelectedMediaItems="value"
        :show="showChooseModal"
        @close="showChooseModal = false"
        @confirm="mediaItemsSelected"
      />
    </template>
  </DefaultField>
</template>

<script>
import Draggable from 'vuedraggable';
import MediaItem from '../../components/MediaItem';
import ChooseMediaModal from '../../modals/ChooseMediaModal';
import { DependentFormField, HandlesValidationErrors } from 'laravel-nova';
import MediaItemContextMenu from '../../components/MediaItemContextMenu';
import HandlesMediaHubFieldValue from '../../mixins/HandlesMediaHubFieldValue';

export default {
  components: { Draggable, MediaItem, ChooseMediaModal, MediaItemContextMenu },
  mixins: [DependentFormField, HandlesValidationErrors, HandlesMediaHubFieldValue],
  props: ['resourceName', 'resourceId', 'field'],

  data: () => ({
    showChooseModal: false,
    showMediaViewModal: false,

    ctxShowEvent: void 0,
    ctxOptions: [],
    ctxMediaItem: void 0,
  }),

  created() {
    this.ctxOptions = [
      { name: this.__('novaMediaHub.contextViewEdit'), action: 'view' },
      { name: this.__('novaMediaHub.contextDownload'), action: 'download' },
    ];
  },

  methods: {
    mediaItemsSelected(mediaItems) {
      this.value = mediaItems;
      this.showChooseModal = false;
    },

    fill(formData) {
      if (this.value && this.value.length) {
        this.value.map((mediaItem, index) => {
          formData.append(`${this.currentField.attribute}[${index}]`, mediaItem.id);
        });
      } else if (this.value && this.value.id) {
        formData.append(this.currentField.attribute, this.value.id);
      } else {
        formData.append(this.currentField.attribute, '');
      }
    },

    openContextMenu(event, mediaItem) {
      this.ctxMediaItem = mediaItem;
      this.ctxShowEvent = event;
    },

    openChooseModal() {
      this.ctxShowEvent = void 0;
      this.showChooseModal = true;
    },
  },

  computed: {
    hasValue() {
      return this.currentField.multiple ? !!this.value?.length : !!this.value;
    },
  },
};
</script>
