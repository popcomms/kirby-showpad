<template>
  <k-radio-field
    name="showpad-tags-mode"
    v-model="value"
    :options="options"
    :columns="2"
    :label="label"
    :help="help"
    @input="onInput"
  />
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: 'Mode'
    },
    help: {
      type: String,
      default: '<em>ALL</em> will fetch assets that are tagged with <u>all</u> of the tags.<br><em>ANY</em> will fetch assets that are tagges with <u>any</u> of the tags.'
    },
    value: {
      type: String,
      default: 'ALL'
    },
  },
  data () {
    return {
      options: [
        { value: 'ALL', text: 'ALL' },
        { value: 'ANY', text: 'ANY' }
      ]
    }
  },
  methods: {
    onInput(event) {
      this.$emit('input', event)
    }
  }
};
</script>
