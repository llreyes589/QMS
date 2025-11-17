<template lang="">
    <div class="card bg-base-100 shadow-xl h-[100vh] overflow-hidden">
        <div class="card-body py-0">
            <!-- Header with Logo and Time -->
            <div
                class="flex flex-col md:flex-row items-center justify-between gap-4"
            >
                <div class="flex items-center gap-4">
                    <img
                        :src="'/images/logo.jpg'"
                        alt="Hardin Medical Clinic"
                        class="h-12 md:h-16 w-auto"
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
                    <div class="text-3xl font-mono font-bold text-primary">
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
                <div ref="tableScroll" class="overflow-x-auto h-[100vh]">
                    <transition name="flip-page" mode="out-in">
                        <table
                            :key="`page-${currentPage}`"
                            class="table table-lg bg-white w-full"
                        >
                            <!-- <thead class="bg-secondary/25 text-base-content">
                                <tr>
                                    <th
                                        class="rounded-tl-lg text-base md:text-5xl"
                                    >
                                        #
                                    </th>
                                    <th class="text-base md:text-5xl">NAME</th>

                                    <th
                                        class="rounded-tr-lg text-base md:text-5xl"
                                    >
                                        ROOM
                                    </th>
                                </tr>
                            </thead> -->
                            <tbody class="font-extrabold">
                                <tr
                                    v-for="(queue, index) in paginatedQueues"
                                    :key="index"
                                    class="hover transition-colors duration-200"
                                    :class="
                                        added_queue.find(
                                            (q) => q.id === queue.id
                                        )?.remaining_time > 0 &&
                                        'animate-pulse bg-secondary text-white'
                                    "
                                >
                                    <th class="font-bold text-base md:text-5xl">
                                        {{
                                            (currentPage - 1) * itemsPerPage +
                                            index +
                                            1
                                        }}
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
                                                {{
                                                    formatTime(queue.created_at)
                                                }}
                                            </span>
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
                                            <p
                                                class="text-base-content/50 text-lg"
                                            >
                                                No queues available
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </transition>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["types", "rooms", "time_interval"],
    mounted() {
        const synth = window.speechSynthesis;
        window.Echo.channel("public-queues").listen(
            ".queue.stored",
            ({ que }) => {
                const interval = this.getTimeInterval / 1000;
                que["remaining_time"] = interval;
                if (synth.onvoiceschanged !== undefined) {
                    synth.onvoiceschanged = this.callQueue({
                        queue: que,
                        synth,
                    });
                } else {
                    // If onvoiceschanged is not supported or already fired, try to populate immediately
                    this.callQueue({ queue: que, synth });
                }
                this.added_queue.push(que);
                let count = 1;
                const callInterval = setInterval(() => {
                    count++;
                    if (count <= 3) {
                        this.callQueue({ queue: que, synth });
                    } else {
                        clearInterval(callInterval);
                    }
                }, 5000);

                // time interval
                this.init();
            }
        );
        window.Echo.channel("public-queues").listen(
            ".queue.inactive",
            ({ _ }) => {
                this.init();
            }
        );
        const queueInterval = setInterval(() => {
            this.added_queue = this.added_queue
                ?.map((q) => {
                    if (q.remaining_time === 0) {
                        this.added_queue?.splice(
                            this.added_queue?.findIndex(
                                (que) => que.id === q.id
                            ),
                            1
                        );
                        // clearInterval(queueInterval);
                    }

                    return {
                        ...q,
                        remaining_time: q.remaining_time - 1,
                    };
                })
                .filter((q) => q.remaining_time > 0);
            // clearInterval(queueInterval);
            // if (count === interval) {
            // }
        }, 1000);

        // Initialize data
        this.init();

        // Start the clock
        this.updateTime();
        this.clockInterval = setInterval(this.updateTime, 1000);
        // Attach scroll listener for auto page flip
        this.$nextTick(() => {
            this.attachScrollListener();
            // start auto flip timer
            this.startAutoFlip();
        });
    },
    unmounted() {
        // Clean up the interval when component is destroyed
        if (this.clockInterval) {
            clearInterval(this.clockInterval);
        }
        // detach scroll listener
        this.detachScrollListener();
        // stop auto flip timer
        this.stopAutoFlip();
    },
    computed: {
        getTimeInterval() {
            return this.time_interval.value;
        },
        paginatedQueues() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return (this.queues || []).slice(start, end);
        },
        totalPages() {
            return Math.max(
                1,
                Math.ceil((this.queues || []).length / this.itemsPerPage)
            );
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
        // Pagination helpers
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.scrollToTopOfTable();
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.scrollToTopOfTable();
            }
        },
        scrollToTopOfTable() {
            const el = this.$refs.tableScroll;
            if (el && el.scrollTop !== undefined) {
                el.scrollTop = 0;
            }
        },
        attachScrollListener() {
            const el = this.$refs.tableScroll;
            if (!el) return;
            // Use an arrow fn so we can remove it later
            this._tableScrollHandler = (e) => this.handleScroll(e);
            el.addEventListener("scroll", this._tableScrollHandler, {
                passive: true,
            });
        },
        detachScrollListener() {
            const el = this.$refs.tableScroll;
            if (el && this._tableScrollHandler) {
                el.removeEventListener("scroll", this._tableScrollHandler);
                this._tableScrollHandler = null;
            }
        },
        handleScroll(e) {
            if (!this.isAutoFlip || this.isFlipping) return;
            const el = e.target;
            // small threshold
            const threshold = 20;
            if (el.scrollTop + el.clientHeight >= el.scrollHeight - threshold) {
                // reached bottom -> go to next page if available
                if (this.currentPage < this.totalPages) {
                    this.isFlipping = true;
                    this.nextPage();
                    // short cooldown to avoid rapid flips
                    setTimeout(() => {
                        this.isFlipping = false;
                    }, 600);
                } else {
                    // if last page, optionally loop to first
                    // this.currentPage = 1;
                }
            }
        },
        // Auto flip controls
        startAutoFlip() {
            // do not start if already running or only one page
            // console.log(this.totalPages <= 1);
            // if (this.autoFlipInterval || this.totalPages <= 1) return;
            this.isAutoFlip = true;
            this.autoFlipInterval = setInterval(() => {
                if (this.currentPage < this.totalPages) {
                    this.isFlipping = true;
                    this.currentPage++;
                    // allow transition to complete
                    setTimeout(() => {
                        this.isFlipping = false;
                    }, 500);
                } else {
                    // loop back to first page for continuous cycling
                    this.isFlipping = true;
                    this.currentPage = 1;
                    setTimeout(() => {
                        this.isFlipping = false;
                    }, 500);
                }
                // ensure table scroll resets
                this.scrollToTopOfTable();
            }, this.autoFlipDelay);
        },
        stopAutoFlip() {
            this.isAutoFlip = false;
            if (this.autoFlipInterval) {
                clearInterval(this.autoFlipInterval);
                this.autoFlipInterval = null;
            }
        },
        toggleAutoFlip() {
            if (this.isAutoFlip) {
                this.stopAutoFlip();
            } else {
                this.startAutoFlip();
            }
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
        callQueue({ queue, synth }) {
            const voices = synth.getVoices();

            const { room_code } = this.findRoomDetails(queue.room_id);
            const text = `${queue.name}: please proceed to room: ${room_code}`;
            // const utterance = new SpeechSynthesisUtterance(text);
            const selectedVoice = voices.find(
                (voice) =>
                    voice.lang === "en-US" && voice.name.includes("Google")
            ); // Or any other criteria
            // console.log({ selectedVoice });
            if (selectedVoice) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.voice = selectedVoice;
                synth.speak(utterance);
            } else {
                console.warn("No suitable voice found. Using default voice.");
                const utterance = new SpeechSynthesisUtterance(text);
                synth.speak(utterance);
            }
        },
    },
    watch: {
        itemsPerPage() {
            // Reset to first page when items per page changes
            this.currentPage = 1;
        },
        queues() {
            // Reset to first page when data changes
            this.currentPage = 1;
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
            added_queue: [],
            countdown_time: 0,
            // pagination
            currentPage: 1,
            itemsPerPage: 8,
            // auto flip controls
            isAutoFlip: true,
            isFlipping: false,
            autoFlipInterval: null,
            // ms between auto flips
            autoFlipDelay: 10000,
        };
    },
};
</script>
<style lang="css">
/* Flip page transition */
.flip-page-enter-active,
.flip-page-leave-active {
    transition: transform 450ms cubic-bezier(0.2, 0.9, 0.2, 1),
        opacity 300ms ease;
}
.flip-page-enter-from {
    transform: translateY(20px) scale(0.99);
    opacity: 0;
}
.flip-page-enter-to {
    transform: translateY(0) scale(1);
    opacity: 1;
}
.flip-page-leave-from {
    transform: translateY(0) scale(1);
    opacity: 1;
}
.flip-page-leave-to {
    transform: translateY(-10px) scale(0.995);
    opacity: 0;
}
</style>
