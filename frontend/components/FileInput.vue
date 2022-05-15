<template>
  <input
    ref="fileInput"
    class="form-control"
    type="file"
    @change="onChange"
  >
</template>

<script>
import Vue from 'vue'

export default Vue.extend({
  name: 'FileInput',
  props: {
    multiple: {
      required: false,
      type: Boolean,
      default: false,
    },
    value: {
      required: false,
      type: [File, Array],
      default: () => null,
    },
  },
  methods: {
    onChange(event) {
      const files = event.target.files
      this.$emit('input', this.multiple ? files : (files[0] || null))
    },
  },
})
</script>

<style scoped>

</style>
