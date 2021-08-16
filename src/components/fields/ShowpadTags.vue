<template>
  <k-tags-field
    accept="options"
    v-model="tags"
    :options="options"
    name="showpad-tags"
    type="tags"
    :label="label"
    :help="help"
    @input="onInput"
  />
</template>

<script>
export default {
  props: {
    help: {
      type: String,
      default: null
    },
    label: {
      type: String,
      default: 'Showpad Tags'
    },
    value: {
      type: String,
      default: null
    },
  },
  data () {
    return {
      options: [],
      tags: []
    }
  },
  computed: {
    formatTags () {
      const array = []
      this.tags.forEach((element) => {
        array.push(element.value)
      })
      return array.join(', ')
    }
  },
  created () {
    this.getTags()
  },
  mounted () {
    setTimeout(() => {
      this.init()
    }, 500)
  },
  methods: {
    init () {
      const array = this.value.split(', ')
      array.forEach(element => {
        const entry = {
          text: element,
          value: element
        }
        this.tags.push(element)
      });
    },
    getTags () {
      this.$api.get('/showpad/tags')
      .then((data) => {
        const array = data.response.items
        array.forEach(element => {
          element.value = element.name
          element.text = element.name
          delete element.name
        })
        this.options = array
      })
    },
    onInput (event) {
      this.$emit("input", this.formatTags);
    }
  }
};
</script>
