<template lang="">
    <div>
        <div class="overflow-x-auto">
            <table class="table table-xl table-zebra">
                <!-- head -->
                <thead>
                    <tr>
                        <th>#</th>
                        <th>WALK-INS</th>
                        <th>Arrival</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- row 1 -->
                    <tr v-for="(queue, index) in queues" :key="index">
                        <th>{{ index + 1 }}</th>
                        <td>{{ queue.name }}</td>
                        <td>{{ queue.created_at }}</td>
                        <td>{{ queue.status }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    mounted() {
        window.Echo.channel("public-queues").listen(
            ".queue.stored",
            (event) => {
                this.init();
            }
        );
        console.log("created");
        this.init();
    },
    methods: {
        async init() {
            try {
                const url = "/get-all-queues";
                const { data } = await axios.post(url);
                this.queues = data.data;
            } catch (error) {
                console.error(error);
            }
        },
    },
    data() {
        return {
            queues: [],
        };
    },
};
</script>
<style lang=""></style>
