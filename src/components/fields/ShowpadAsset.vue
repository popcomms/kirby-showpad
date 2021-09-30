<template>
  <k-grid class="showpad-asset-field-grid">
    <k-column width="5/6">
      <k-text-field
        v-model="url"
        name="showpad_app_link"
        :label="label"
        :placeholder="placeholder"
        :required="false"
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
          v-if="url && !loading"
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
    field: {
      type: String,
      default: null
    },
    value: {
      type: String,
      default: null
    },
    endpoints: {
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
  mounted () {
    if (this.value) {
      const json = JSON.parse(this.value)
      this.url = json.appLink
    }
  },
  watch: {
    value (value) {
      const json = JSON.parse(this.value)
      this.url = json.appLink
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
          this.value = JSON.stringify(data.response)
          this.$emit("input", this.value);
          if (this.field) {
            this.saveAsset(data.response)
          }
        })
        .catch((error) => {
          this.loading = false
          this.error = error.message
          console.log(error)
        })
    },
    saveAsset (asset) {
      const page = this.endpoints.model.replace('pages/', '')
      const params = '?u=' + asset.downloadLink + '&n=' + asset.name + '&p=' + page.replace(/\+/g, '/') + '&f=' + this.field

      console.log('page', this.endpoints);

      this.$api.get('/showpad/asset-save' + params)
        .then((data) => {
          console.log('data', data)
          console.log('/showpad-save-asset/', data.response)
          if (data.blueprint !== 'Stepper') {
            location.reload()
          }
          // location.reload()
        })
        .catch((error) => {
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
