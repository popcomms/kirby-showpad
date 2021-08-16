<template>
  <k-grid class="showpad-asset-field-grid">
    <k-column width="5/6">
      <k-url-field
        v-model="url"
        name="url"
        :label="label"
        :placeholder="placeholder"
      />
    </k-column>
    <k-column width="1/6">
      <k-headline>&nbsp;</k-headline>
      <div class="showpad-asset-field">
        <k-button
          v-html="'Fetch'"
          @click="fetchBySlug"
        />
        <k-icon
          v-if="loading"
          type="loader"
          size="small"
          class="spin"
        />
        <k-icon
          v-if="asset"
          type="check"
          size="small"
          style="color: green"
        />
      </div>
    </k-column>
    <k-column width="1/1">
      <k-info-field
        v-show="error"
        theme="negative"
        :text="error"
      />
    </k-column>
  </k-grid>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: 'Showpad App Link'
    },
    placeholder: {
      type: String,
      default: 'e.g. showpad://file/ed009ca8-cfa6-4286-86ca-e07bc4764588'
    },
    value: {
      type: Object,
      default: null
    }
  },
  data () {
    return {
      loading: false,
      error: null,
      url: ''
    }
  },
  created () {
    if (this.value) {
      this.url = this.value.appLink
    }
  },
  methods: {
    fetchBySlug () {
      if (this.url) {
        this.error = null
        this.loading = true
        const slug = this.url.replace('showpad://file/', '')

        this.$api.get('/showpad/asset-slug/' + slug)
        .then((data) => {
          console.log(data)
          this.fetchById(data.response.items[0].id)
        })
        .catch((error) => {
          this.loading = false
          this.error = error.message
          console.log(error)
        })
      } else {
        this.error = 'Please input a Showpad AppLink URL'
      }
    },
    fetchById (id) {
      this.$api.get('/showpad/asset/' + id)
        .then((data) => {
          this.loading = false
          this.value = data.response
          console.log(data)
        })
        .then(() => {
          this.$emit("input", this.value);
        })
        .catch((error) => {
          this.loading = false
          this.error = error.message
          console.log(error)
        })
    }
  }
};
</script>

<style>
  .showpad-asset-field-grid {
    grid-row-gap: 0.5rem;
  }
  .showpad-asset-field {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 1rem;
  }
  .spin {
    color: green;
    animation: spin 1s linear infinite;
  }
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>
