<template>
  <header class="main-header">
    <div class="main-header__logo-wrapper">
      <a href="/">
        <img :src="options.header_logo" alt="Algorythm of Success logo" />
      </a>
    </div>

    <nav-menu class="header-menu" name="main" :menuLinks="options.header_nav" />
    <div class="main-header__contacts-wrapper">
      <div class="main-header__contacts">
        <a :href="'tel:+' + options.header_number">{{
          options.header_number
        }}</a>
        <a :href="'mailto:' + options.header_email">{{
          options.header_email
        }}</a>
      </div>

      <button>Заказать звонок</button>
    </div>
  </header>
</template>

<script>
import NavMenu from "@/components/header/NavMenu";

export default {
  components: {
    NavMenu,
  },
  props: {
    options: {
      type: Object,
      require: true,
    },
  },
};
</script>

<style lang="scss">
@import "@/scss/header/index.scss";
</style>
