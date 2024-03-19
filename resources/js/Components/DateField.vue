<template>
  <span>{{ formatedDate }}</span>
</template>

<script>
import { defineComponent } from "vue";
import dayjs from "dayjs";

export default defineComponent({
  props: {
    date: {
      type: String,
      default: "",
    },
    toFormat: {
      type: String,
      default: "DD/MM/YYYY",
    },
  },
  computed: {
    formatedDate() {
      if (this.date == null) {
        return "";
      }
      return dayjs(this.date).format(this.toFormat);
    },
  },
});
</script>
