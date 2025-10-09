<template lang="">
    <div class="card bg-base-100 shadow-xl min-h-screen">
        <div class="card-body p-4 md:p-8">
            <!-- Header with Logo -->
            <div
                class="flex flex-col md:flex-row items-center gap-4 mb-6 pb-6 border-b"
            >
                <!-- <img
                    src="/company-logo.svg"
                    alt="QMS Logo"
                    class="h-12 md:h-16 w-auto"
                /> -->
                <div class="flex flex-col text-center md:text-left">
                    <h1 class="text-2xl md:text-3xl font-bold text-primary">
                        Queue Management
                    </h1>
                    <p class="text-base-content/70">Add New Queue</p>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-6 md:gap-8">
                <div
                    class="w-full space-y-6 bg-base-200 p-4 md:p-6 rounded-xl shadow-inner order-2 lg:order-1"
                >
                    <div class="form-control">
                        <label class="label">
                            <span
                                class="label-text text-base md:text-lg font-semibold"
                                >Select Room</span
                            >
                        </label>
                        <select
                            class="select select-bordered w-full bg-white"
                            v-model="form.room_id"
                        >
                            <option disabled selected value="">
                                Choose a room
                            </option>
                            <option
                                v-for="(room, index) in rooms"
                                :key="index"
                                :value="room.id"
                                :disabled="room.active_queue"
                                class="font-medium"
                            >
                                {{ room.room_code }}
                            </option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span
                                class="label-text text-base md:text-lg font-semibold"
                                >Walk-in Name</span
                            >
                        </label>
                        <input
                            type="text"
                            class="input input-bordered w-full bg-white"
                            placeholder="Enter name here"
                            v-model="form.name"
                        />
                    </div>
                    <div class="pt-4">
                        <button
                            class="btn btn-primary w-full btn-lg gap-2"
                            @click="handleSubmit"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
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
                            Add to Queue
                        </button>
                    </div>
                </div>
                <div
                    class="bg-base-200 rounded-xl p-4 md:p-6 shadow-inner order-1 lg:order-2"
                >
                    <div class="overflow-x-auto">
                        <table class="table table-zebra bg-white">
                            <thead class="bg-base-300 text-base-content">
                                <tr>
                                    <th class="rounded-tl-lg">#</th>
                                    <th>ROOM</th>
                                    <th class="hidden md:table-cell">
                                        WALK-INS
                                    </th>
                                    <th class="hidden lg:table-cell">
                                        ARRIVAL
                                    </th>
                                    <th class="rounded-tr-lg">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(queue, index) in queues"
                                    :key="index"
                                    class="hover transition-colors duration-200"
                                >
                                    <th class="font-bold">{{ index + 1 }}</th>
                                    <td class="font-medium">
                                        <div class="badge badge-lg">
                                            {{
                                                rooms.find(
                                                    (r) =>
                                                        r.id === queue.room_id
                                                ).room_code
                                            }}
                                        </div>
                                    </td>
                                    <td
                                        class="font-medium hidden md:table-cell"
                                    >
                                        {{ queue.name }}
                                    </td>
                                    <td
                                        class="font-medium hidden lg:table-cell"
                                    >
                                        <div class="flex items-center gap-2">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-primary"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            {{ formatTime(queue.created_at) }}
                                        </div>
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-error btn-sm gap-2"
                                            type="button"
                                            @click="
                                                handleShowConfirmModal(queue)
                                            "
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                            <span class="hidden sm:inline"
                                                >Set Inactive</span
                                            >
                                        </button>
                                    </td>
                                </tr>
                                <!-- Empty state -->
                                <tr v-if="queues.length === 0">
                                    <td colspan="5" class="text-center py-8">
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
                                                No active queues
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

        <!-- modal -->
        <dialog id="confirmation-modal" class="modal">
            <div class="modal-box bg-base-200">
                <div class="flex items-center gap-3 text-error">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-12 w-12"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                    <h3 class="text-2xl font-bold">Warning!</h3>
                </div>
                <p class="py-6 text-lg">
                    You are about to set this queue to inactive. This action is
                    irreversible.
                </p>
                <div class="modal-action">
                    <form method="dialog" class="flex gap-3 w-full">
                        <button
                            class="btn btn-outline flex-1 btn-lg"
                            @click="handleCancelSetInactive"
                        >
                            Cancel
                        </button>
                        <button
                            class="btn btn-error flex-1 btn-lg"
                            @click="handleConfirmSetInactive"
                        >
                            Confirm
                        </button>
                    </form>
                </div>
            </div>
        </dialog>
    </div>
</template>
<script>
import axios from "axios";
export default {
    mounted() {
        this.init();
    },
    data() {
        return {
            form: {
                room_id: "",
                name: "",
            },
            queues: [],
            selected_queue: [],
            rooms: [],
        };
    },
    methods: {
        formatTime(timestamp) {
            if (!timestamp) return "";
            const date = new Date(timestamp);
            return date.toLocaleString("en-US", {
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
                month: "short",
                day: "numeric",
            });
        },
        async handleSubmit() {
            try {
                const url = "/queues";
                const fd = new FormData();
                fd.append("room_id", this.form.room_id);
                fd.append("name", this.form.name);
                const { data } = await axios.post(url, fd);
                this.init();
                this.clearInputs();
            } catch (error) {
                console.error(error);
            }
        },
        async init() {
            try {
                const promises = await Promise.allSettled([
                    this.getQueues(),
                    this.getRooms(),
                ]);
                const [queuesData, roomsData] = promises.map((result) =>
                    result.status === "fulfilled"
                        ? result.value
                        : { data: null }
                );
                this.queues = queuesData.data;
                this.rooms = roomsData.data;
            } catch (error) {
                console.error(error);
            }
        },
        async getQueues() {
            try {
                const url = "/get-all-queues";
                const { data } = await axios.post(url);
                return data;
            } catch (error) {
                console.error(error);
            }
        },
        async getRooms() {
            try {
                const url = "/get-all-rooms";
                const { data } = await axios.post(url);
                return data;
            } catch (error) {
                console.error(error);
            }
        },
        clearInputs() {
            this.form.name = "";
            this.form.room_id = "";
        },
        handleShowConfirmModal(queue) {
            this.selected_queue = queue;
            document.getElementById("confirmation-modal").showModal();
        },
        handleCancelSetInactive() {
            this.selected_queue = [];
            document.getElementById("confirmation-modal").hideModal();
        },
        async handleConfirmSetInactive() {
            const url = `/queues/${this.selected_queue.id}`;
            try {
                const { data } = await axios({
                    url,
                    method: "DELETE",
                    data: null,
                });
                this.init();
            } catch (error) {
                console.error(error);
            }
        },
    },
};
</script>
<style lang=""></style>
