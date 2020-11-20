<template>
    <div>
        <v-row>
            <v-col cols="4">
                <photo_desserts></photo_desserts>

            </v-col>
        </v-row>
    </div>

</template>

<script>



import photo_desserts from "./images/photo_desserts";
export default {
    name: "index",
    components:{
        photo_desserts,
    }

}
</script>

<style scoped>

</style>
