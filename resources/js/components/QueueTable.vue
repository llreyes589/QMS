<template lang="">
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <h2 class="card-title text-2xl font-bold">Current Queue</h2>
                    <div class="badge badge-primary badge-lg">Live Updates</div>
                </div>
                <div class="stats shadow">
                    <div class="stat">
                        <div class="stat-title">Total Queue</div>
                        <div class="stat-value text-primary">{{ queues.length }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-base-200 rounded-xl p-6 shadow-inner">
                <div class="overflow-x-auto">
                    <table class="table table-lg bg-white">
                        <thead class="bg-base-300 text-base-content">
                            <tr>
                                <th class="rounded-tl-lg text-lg">#</th>
                                <th class="text-lg">WALK-INS</th>
                                <th class="text-lg">ARRIVAL TIME</th>
                                <th class="rounded-tr-lg text-lg">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(queue, index) in queues" :key="index" class="hover">
                                <th class="font-bold text-lg">{{ index + 1 }}</th>
                                <td class="font-medium text-lg">{{ queue.name }}</td>
                                <td class="font-medium">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ queue.created_at }}
                                    </div>
                                </td>
                                <td>
                                    <div class="badge" :class="{
                                        'badge-success': queue.status === 'active',
                                        'badge-error': queue.status === 'inactive',
                                        'badge-warning': queue.status === 'pending'
                                    }">
                                        {{ queue.status }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
