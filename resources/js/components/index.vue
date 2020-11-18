<template>
    <div>
        <photo_cold_dishes></photo_cold_dishes>
        <v-row>
            <v-col cols="4">
                <photo_cold_drinks></photo_cold_drinks>
            </v-col>
        </v-row>
    </div>

</template>

<script>


import photo_cold_drinks from "./images/photo_cold_drinks";

export default {
    name: "index",
    components:{
        photo_cold_drinks,
    }

}
</script>

<style scoped>

</style>
