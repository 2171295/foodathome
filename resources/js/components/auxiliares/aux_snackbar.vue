<template>
  <v-snackbar
    v-model="snackbar"
    :bottom="y === 'bottom'"
    :color="color"
    :left="x === 'left'"
    :multi-line="mode === 'multi-line'"
    :timeout="timeout"
    :top="y === 'top'"
    :vertical="mode === 'vertical'">
    {{ text }}
    <v-btn dark text @click="snackbar = false">
      <v-icon>mdi-close</v-icon>
    </v-btn>
  </v-snackbar>
</template>

<script>
export default {
name: "aux_snackbar",
  props:['text','color','snackbar'],
  data: () => {
    return {
      // ---- SNACKBAR INFO -----
      mode: '',
      timeout: 3000,
      x: null,
      y: 'top',
      // ------------------------
    }
  }

}
</script>

<style scoped>

</style>
