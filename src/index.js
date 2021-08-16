import ShowpadAsset from './components/fields/ShowpadAsset.vue'
import ShowpadTags from './components/fields/ShowpadTags.vue'
import ShowpadTagsMode from './components/fields/ShowpadTagsMode.vue'

panel.plugin('popcomms/kirby-showpad', {
  fields: {
    'showpad-asset': ShowpadAsset,
    'showpad-tags': ShowpadTags,
    'showpad-tags-mode': ShowpadTagsMode
  }
});
