<template>
  <button class="button">{{ text }}</button>
</template>

<script>
export default {
  props: {
    text: {
      type: String,
      required: true,
    },
  },
};
</script>

<style lang="scss">
.button {
  height: 40px;

  margin: 0;
  padding: 5px 40px;

  border-radius: 10px;
  border-color: orangered;

  outline: none;
}
</style>
