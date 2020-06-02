<template>
  <main>
    <Modal ref="modal1" :show="isOpenModal" @closeModal="onCloseModal" />
    <button @click="openModal"><h1>open modal</h1></button>
    <div v-if="page" class="main-page">
      <section class="hero">
        <div class="background-image">
          <img
            :src="page.ACF.hero_image.url"
            class=""
            :alt="page.ACF.hero_image.alt"
          />
        </div>
        <div class="container">
          <h1>{{ page.ACF.hero_title }}</h1>
          <div>{{ page.ACF.hero_text }}</div>
          <Button :text="page.ACF.hero_button_text" />
        </div>
      </section>

      <section class="features">
        <button type="button" class="btn btn-primary">Главный</button>
        <h2 class="visualli-hidden">Features</h2>
        <div class="container">
          <div class="features__list">
            <div v-for="item in page.ACF.features" class="features__item">
              <img :src="item.icon" alt="" />
              <p>{{ item.header }}</p>
              <p v-if="item.caption">{{ item.caption }}</p>
            </div>
          </div>
        </div>
      </section>

      <section>
        <v-container>
          <v-row justify="center">
            <v-col cols="2"
              ><v-card class="pa-2" outlined tile>
                One of three columns
              </v-card></v-col
            >
            <v-col cols="6"
              ><v-card class="pa-2" outlined tile>
                One of three columns
              </v-card></v-col
            >
            <v-col cols="2"
              ><v-card class="pa-2" outlined tile>
                One of three columns
              </v-card></v-col
            >
          </v-row>
        </v-container>
      </section>
    </div>
  </main>
</template>

<script>
import Modal from "@/components/template-parts/Modal.vue";

export default {
  components: {
    Modal,
  },
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
      isOpenModal: false,
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
    openModal() {
      this.isOpenModal = true;
      //document.body.classList.add("modal-open");

      this.updateBody("add");
    },
    onCloseModal() {
      this.isOpenModal = false;
      document.body.classList.remove("modal-open");

      //this.updateBody("remove");
    },
    updateBody(toggle) {
      let onTransitionEnd;
      switch (toggle) {
        case "add":
          onTransitionEnd = () => {
            document.body.classList.add("modal-open");
            this.$refs.modal1.$el.removeEventListener(
              "transitionend",
              onTransitionEnd
            );
          };
          break;
        case "remove":
          onTransitionEnd = () => {
            document.body.classList.remove("modal-open");
            this.$refs.modal1.$el.removeEventListener(
              "transitionend",
              onTransitionEnd
            );
          };
          break;
        default:
          onTransitionEnd = () => {
            document.body.classList.remove("modal-open");
            this.$refs.modal1.$el.removeEventListener(
              "transitionend",
              onTransitionEnd
            );
          };
          break;
      }
      this.$refs.modal1.$el.addEventListener("transitionend", onTransitionEnd);
    },
  },
  created() {
    this.getPage();
  },
};
</script>
