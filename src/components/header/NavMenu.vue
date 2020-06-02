<template>
  <nav>
    <ul>
      <li v-for="link in menuLinks" :key="link.section_id">
        <a :href="'#' + link.section_id">{{ link.text }}</a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: "NavMenu",
  props: {
    name: {
      type: String,
      required: true,
    },
    menuLinks: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {};
  },
  computed: {
    menu() {
      return this.$store.getters.menu({ name: this.name });
    },
  },
};
</script>

<style lang="scss">
@import "../../scss/header/menu.scss";
</style>
