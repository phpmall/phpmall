<template>
  <a v-bind="$attrs" :href="to" :target="target">
    <slot />
  </a>
  
</template>

<script lang="ts">
import { RouterLink } from 'vue-router'

export default {
  name: 'HtmlLink',
  inheritAttrs: false,

  props: {
    /** @ts-ignore */
    ...RouterLink.props,
    inactiveClass: String,
    target: String,
  }
}
</script>
