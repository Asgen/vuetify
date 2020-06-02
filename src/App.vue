<template>
  <div id="vue-wordpress-app v-application" @click="handleClicks">
    <main-header :options="options" />
    <transition name="fade" mode="out-in" @after-leave="updateScroll">
      <router-view :key="$route.path" />
    </transition>
    <transition name="fade">
      <site-loading v-if="loading" />
    </transition>
  </div>
</template>

<script>
import SiteLoading from "@/components/utility/SiteLoading";
import MainHeader from "@/components/header/MainHeader.vue";

export default {
  components: {
    SiteLoading,
    MainHeader,
  },
  data() {
    return {
      site: this.$store.state.site,
      request: {
        type: "pages",
        slug: "home",
      },
    };
  },
  computed: {
    loading() {
      return this.$store.state.site.loading;
    },
    options() {
      return this.$store.state.pages[Object.keys(this.$store.state.pages)[0]]
        .ACF_options;
    },
  },
  methods: {
    getLinkEl(el) {
      while (el.parentNode) {
        if (el.tagName === "A") return el;
        el = el.parentNode;
      }
    },
    handleClicks(e) {
      const a = this.getLinkEl(e.target);
      if (a && a.href.includes(this.site.url)) {
        const {
          altKey,
          ctrlKey,
          metaKey,
          shiftKey,
          button,
          defaultPrevented,
        } = e;
        // don't handle if has class 'no-router'
        if (a.className.includes("no-router")) return;
        // don't handle with control keys
        if (metaKey || altKey || ctrlKey || shiftKey) return;
        // don't handle when preventDefault called
        if (defaultPrevented) return;
        // don't handle right clicks
        if (button !== undefined && button !== 0) return;
        // don't handle if `target="_blank"`
        if (a.target && a.target.includes("_blank")) return;
        // don't handle same page links
        let currentURL = new URL(a.href, window.location.href);
        if (currentURL && currentURL.pathname === window.location.pathname) {
          // if same page link has same hash prevent default reload
          if (currentURL.hash === window.location.hash) e.preventDefault();
          return;
        }
        // Prevent default and push to vue-router
        e.preventDefault();
        let path = a.href.replace(this.site.url, "");
        this.$router.push(path);
      }
    },
    updateScroll() {
      window.scroll(0, 0);
    },
  },
};
</script>

<style lang="scss">
body.modal-open {
  overflow: hidden;
}
/* Vue transition classes
-------------------------------------------- */

.fade-enter-active {
  transition: opacity 0.4s cubic-bezier(0.4, 0, 0, 1);
}

.fade-leave-active {
  transition: opacity 0.2s cubic-bezier(0.4, 0, 0, 1);
}

.fade-enter-to,
.fade-leave {
  opacity: 1;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
