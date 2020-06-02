<template>
  <transition name="fade">
    <div v-show="show" class="popup">
      <div class="popup__layer">
        <div class="popup__inner">
          <div class="popup__content">
            <h1>hello modal</h1>
            <v-btn @click="$emit('closeModal')">Close</v-btn>
            <button @click="$emit('closeModal')">Close Modal</button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      default: false,
    },
  },
};
</script>

<style lang="scss" scoped>
.popup {
  width: 100vw;
  height: 100vh;
  position: fixed;

  top: 0;
  left: 0;
  overflow: hidden;

  z-index: 100;

  &__layer {
    position: absolute;
    width: 100%;
    height: 100%;

    background-color: orchid;
  }

  &__inner {
    position: absolute;
    width: 50vw;
    min-height: 50vh;

    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);

    background-color: palegreen;
  }
}
</style>
