<template>
  <drop-zone class="drop-area" @files-dropped="addFile" #default="{ dropZoneActive }">
    <div class="flex justify-center items-center w-full">
      <label for="dropzone-file"
        class="flex flex-col justify-center items-center w-full h-64 rounded-lg cursor-pointer border-2 border-gray-200 border-dashed hover:border-gray-600"
        :class="dropZoneActive ? 'bg-blue-100 border-blue-600' : ''">
        <div v-if="!modelValue" class="flex flex-col justify-center items-center pt-5 pb-6">
          <svg class="mb-3 w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
          </svg>
          <p class="mb-2 text-sm">
            <span class="font-semibold">Clique para anexar</span> ou arraste e solte
          </p>
          <p class="text-xs text-gray-500">{{ description }}</p>
        </div>
        <div v-else
          class="flex flex-col justify-center items-center pt-5 pb-6 px-4 border border-gray-200 cursor-pointer shadow-sm sm:rounded-md hover:border-gray-500">
          <span class="text-xs text-gray-500">Nome: {{ modelValue.name }}</span>
        </div>
        <input id="dropzone-file" type="file" class="hidden" @change="onChange" ref="input" />
      </label>
    </div>
  </drop-zone>
</template>

<script>
import { defineComponent } from "vue";
import DropZone from "./DropZone.vue";

export default defineComponent({
  components: { DropZone },
  props: ["modelValue", "description"],
  emits: ["update:modelValue"],
  data() {
    return {
      configCurrency: {
        output: "byte",
        base: "binary",
        average: true,
      },
    };
  },
  methods: {
    addFile(file) {
      this.$emit("update:modelValue", file);
    },
    onChange(event) {
      this.$emit("update:modelValue", event.target.files[0]);
    },
  },
});
</script>
