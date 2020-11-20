<template>
    <div>
        <div  id="h2" style="text-align: center">What do you feel like eating today?</div>
        <v-row>
            <v-col style="text-align: center">
                <v-card @click="menuHotDishes" elevation="0" >
                    <v-card-text>
                        <photo_hot_dishes/>
                        <p>Hot Dishes</p>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col style="text-align: center">
                <v-card @click="menuColdDishes" elevation="0" >
                    <v-card-text>
                        <photo_cold_dishes/>
                        <p>Cold Dishes</p>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col style="text-align: center">
                <v-card @click="menuDesserts" elevation="0" >
                    <v-card-text>
                        <photo_desserts/>
                        <p>Desserts</p>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col style="text-align: center">
                <v-card @click="menuColdDrinks" elevation="0" >
                    <v-card-text>
                        <photo_cold_drinks/>
                        <p>Cold Drinks</p>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col style="text-align: center">
                <v-card @click="menuHotDrinks" elevation="0" >
                    <v-card-text>
                        <photo_hot_drinks/>
                        <p>Hot Drinks</p>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

    </div>

</template>

<script>
import photo_cold_drinks from "./images/photo_cold_drinks"
import photo_hot_drinks from "./images/photo_hot_drinks";
import photo_hot_dishes from "./images/photo_hot_dishes";
import photo_cold_dishes from "./images/photo_cold_dishes";
import photo_desserts from "./images/photo_desserts";
export default {
    name: "index",
    components:{
        photo_desserts,
        photo_cold_dishes,
        photo_hot_dishes,
        photo_hot_drinks,
        photo_cold_drinks
    },
    methods:{
        menuHotDishes(){
            console.log("Olá")
        },
        menuColdDishes(){

        },
        menuDesserts(){

        },
        menuColdDrinks(){

        },
        menuHotDrinks(){

        }
    }

}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap');

#h2{
    font-family: "Montserrat";
    font-size: 30px;
    font-weight: bold;
}

</style>
