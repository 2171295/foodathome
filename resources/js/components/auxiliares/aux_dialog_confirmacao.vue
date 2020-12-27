<template>
  <v-dialog @keydown.esc="cancel" v-model="dialog" max-width="300px">
    <v-card>
      <v-toolbar>
        <v-toolbar-title>
          {{title}}
        </v-toolbar-title>
      </v-toolbar>
        <v-card-text v-html="text"  class="pa-4 black--text">
        </v-card-text>
        <v-card-actions class="pt-3">
          <v-spacer></v-spacer>
          <v-btn
            color="grey"
            text
            class="body-2 font-weight-bold"
            @click.native="cancel"
          >Cancelar</v-btn>
          <v-btn
            color="success"
            class="body-2 font-weight-bold"
            outlined
            @click.native="agree"
          >Aceitar</v-btn>
        </v-card-actions>
    </v-card>

  </v-dialog>
</template>

<script>
export default {
name: "aux_dialog_confirmacao",
  data:function () {
    return {
      title:'',
      text:'',
      dialog:false,
      reject:null,
      resolve:null,
    }
  },
  methods:{
    open(title, message) {
      this.dialog = true;
      this.title = title;
      this.text = message;
      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
      });
    },
    cancel(){
      this.resolve(false);
      this.dialog = false;
    },
    agree(){
      this.resolve(true);
      this.dialog = false;
    }
  }
}
</script>

<style scoped>

</style>
