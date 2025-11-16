<template lang="">
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body p-4 md:p-8">
            <!-- Header with Logo and Time -->
            <div
                class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 pb-6 border-b"
            >
                <div class="flex items-center gap-4">
                    <img
                        :src="'/images/logo.jpg'"
                        alt="Hardin Medical Clinic"
                        class="h-12 md:h-28 w-auto"
                    />
                </div>
                <!-- <div class="flex items-center gap-4">
                    <div
                        class="badge badge-secondary badge-xl rounded-5xl animate-pulse text-5xl pt-6 px-6 pb-8"
                    >
                        {{ types.find((t) => t.id === type).name }}
                    </div>
                </div> -->
                <div class="flex flex-col items-end">
                    <div class="text-5xl font-mono font-bold text-primary">
                        {{ currentDate }}
                    </div>
                </div>
            </div>

            <!-- Queue Status Header -->
            <!-- <div
                class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6"
            >
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <h2 class="card-title text-5xl font-bold">Current Type:</h2>
                    <div
                        class="badge badge-secondary badge-xl animate-pulse text-3xl p-6"
                    >
                        {{ types.find((t) => t.id === type).name }}
                    </div>
                </div>
                <div class="stats shadow bg-base-200">
                    <div class="stat px-6">
                        <div class="stat-title">Total in Queue</div>
                        <div class="stat-value text-primary">
                            {{ queues.length }}
                        </div>
                        <div class="stat-desc">Updated in Real-time</div>
                    </div>
                </div>
            </div> -->

            <div class="bg-base-200 rounded-xl p-4 md:p-2 shadow-inner">
                <div class="overflow-x-auto">
                    <table class="table table-lg bg-white w-full table-zebra">
                        <thead class="bg-secondary/25 text-base-content">
                            <tr>
                                <th class="rounded-tl-lg text-base md:text-5xl">
                                    #
                                </th>
                                <th class="text-base md:text-5xl">NAME</th>
                                <th
                                    class="text-base md:text-5xl hidden md:table-cell"
                                >
                                    ARRIVAL TIME
                                </th>
                                <th class="rounded-tr-lg text-base md:text-5xl">
                                    ROOM
                                </th>
                            </tr>
                        </thead>
                        <tbody class="font-extrabold">
                            <tr
                                v-for="(queue, index) in queues"
                                :key="index"
                                class="hover transition-colors duration-200"
                                :class="
                                    queue?.id === added_queue?.id &&
                                    'animate-pulse'
                                "
                            >
                                <th class="font-bold text-base md:text-5xl">
                                    {{ index + 1 }}
                                </th>
                                <td class="text-base md:text-5xl">
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-1 md:gap-2"
                                    >
                                        <span>{{ queue.name }}</span>
                                        <!-- Mobile-only arrival time -->
                                        <span
                                            class="text-base-content/70 md:hidden"
                                        >
                                            {{ formatTime(queue.created_at) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell">
                                    <div
                                        class="flex items-center gap-2 md:text-5xl"
                                    >
                                        🕑
                                        {{ formatTime(queue.created_at) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="md:text-5xl">
                                        {{
                                            findRoomDetails(queue.room_id)
                                                .room_code
                                        }}
                                    </div>
                                </td>
                            </tr>
                            <!-- Empty state -->
                            <tr v-if="queues.length === 0">
                                <td colspan="4" class="text-center py-8">
                                    <div
                                        class="flex flex-col items-center gap-2"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-12 w-12 text-base-content/30"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            />
                                        </svg>
                                        <p class="text-base-content/50 text-lg">
                                            No queues available
                                        </p>
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
    props: ["types", "rooms", "time_interval"],
    mounted() {
        // Initialize websocket listener
        console.log(this.getTimeInterval);
        window.Echo.channel("public-queues").listen(
            ".queue.stored",
            ({ que }) => {
                this.added_queue = que;
                const { room_code } = this.findRoomDetails(que.room_id);
                const text = `${que.name}: please proceed to room: ${room_code}`;
                const utterance = new SpeechSynthesisUtterance(text);
                speechSynthesis.speak(utterance);

                // time interval
                let count = 0;
                const queueInterval = setInterval(() => {
                    count++;
                    if (count % 2 === 0) speechSynthesis.speak(utterance);
                    // console.log({ count });
                    if (count === this.getTimeInterval / 1000) {
                        this.added_queue = null;
                        clearInterval(queueInterval);
                    }
                }, this.getTimeInterval);

                this.init();
            }
        );

        // Initialize data
        this.init();

        // Start the clock
        this.updateTime();
        this.clockInterval = setInterval(this.updateTime, 1000);
    },
    unmounted() {
        // Clean up the interval when component is destroyed
        if (this.clockInterval) {
            clearInterval(this.clockInterval);
        }
    },
    computed: {
        getTimeInterval() {
            return this.time_interval.value;
        },
    },
    methods: {
        async init() {
            try {
                const url = `/get-all-queues`;
                const { data } = await axios.post(url);
                this.queues = data.data;
            } catch (error) {
                console.error(error);
            }
        },
        updateTime() {
            const now = new Date();

            // Update time in HH:MM:SS format
            this.currentTime = now.toLocaleTimeString("en-US", {
                hour12: false,
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
            });

            // Update date in full format
            this.currentDate = now.toLocaleDateString("en-US", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        },
        formatTime(timestamp) {
            if (!timestamp) return "";
            const date = new Date(timestamp);
            return date.toLocaleString("en-US", {
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
            });
        },
        findRoomDetails(room_id) {
            return this.rooms.find((t) => t.id === room_id);
        },
    },
    data() {
        return {
            queues: [],
            currentTime: "",
            currentDate: "",
            clockInterval: null,
            type: 1,
            toogleType: null,
            added_queue: null,
            countdown_time: 0,
        };
    },
};
</script>
<style lang=""></style>
