<template>
  <main>
    <div v-if="page" class="main-page">
      <h1>yoyo</h1>
    </div>
  </main>
</template>

<script>
export default {
  name: "Page",
  props: {
    slug: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      request: {
        type: "pages",
        slug: this.slug,
        showLoading: true,
      },
    };
  },
  computed: {
    page() {
      return this.$store.getters.singleBySlug(this.request);
    },
  },
  methods: {
    getPage() {
      this.$store.dispatch("getSingleBySlug", this.request).then(() => {
        if (this.page) {
          this.$store.dispatch("updateDocTitle", {
            parts: [this.page.title.rendered, this.$store.state.site.name],
          });
        } else {
          this.$router.replace("/404");
        }
      });
    },
  },
  created() {
    this.getPage();
  },
};
</script>

<style>
@import "../scss/index.scss";
@import "../scss/features.scss";

.hero {
  position: relative;
}

.background-image {
  position: absolute;
  width: 100%;
  height: 100%;

  overflow: hidden;

  img {
    width: 100%;
    height: auto;

    overflow: hidden;
  }
}
</style>
