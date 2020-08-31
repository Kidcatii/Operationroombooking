<template>
    <v-app>
        <v-card>
            <v-container style="center">
                <p style="text-center">จองเรียบร้อย</p>
            </v-container>
        </v-card>
    </v-app>
</template>


<script>
export default {
    middleware: 'auth',
    name:'reserved',
    mounted() {
    setTimeout(() => {
       this.$router.push('/home')
    }, 2000)
}
}
</script>