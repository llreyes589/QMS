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
                    <tr v-for="(queue, index) in getQueues" :key="index">
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
    props: ["queues"],
    computed: {
        getQueues() {
            return this.queues;
        },
    },
};
</script>
<style lang=""></style>
