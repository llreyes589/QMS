<template lang="">
    <div class="card bg-base-100 shadow-xl h-[100vh]">
        <div class="card-body">
            <div class="flex items-center gap-4 mb-6">
                <h2 class="card-title text-2xl font-bold">Queue Management</h2>
                <span class="badge badge-primary badge-lg">Add New Queue</span>
            </div>
            <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-8">
                <div class="w-100 space-y-6 bg-base-200 p-6 rounded-xl shadow-inner">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-lg font-semibold">Select Room</span>
                        </label>
                        <select class="select select-bordered w-full bg-white" v-model="form.room_id">
                            <option disabled selected value="">Choose a room</option>
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
                            <span class="label-text text-lg font-semibold">Walk-in Name</span>
                        </label>
                        <input
                            type="text"
                            class="input input-bordered w-full bg-white"
                            placeholder="Enter name here"
                            v-model="form.name"
                        />
                    </div>
                    <div class="pt-4">
                        <button class="btn btn-primary w-full btn-lg gap-2" @click="handleSubmit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add to Queue
                        </button>
                    </div>
                </div>
                <div class="bg-base-200 rounded-xl p-6 shadow-inner">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra bg-white">
                            <thead class="bg-base-300 text-base-content">
                                <tr>
                                    <th class="rounded-tl-lg">#</th>
                                    <th>ROOM</th>
                                    <th>WALK-INS</th>
                                    <th>ARRIVAL TIME</th>
                                    <th class="rounded-tr-lg">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(queue, index) in queues" :key="index" class="hover">
                                    <th class="font-bold">{{ index + 1 }}</th>
                                    <td class="font-medium">
                                        <div class="badge badge-lg">{{
                                            rooms.find(
                                                (r) => r.id === queue.room_id
                                            ).room_code
                                        }}</div>
                                    </td>
                                    <td class="font-medium">{{ queue.name }}</td>
                                    <td class="font-medium">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ queue.created_at }}
                                        </div>
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-error btn-sm gap-2"
                                            type="button"
                                            @click="handleShowConfirmModal(queue)"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Set Inactive
                                        </button>
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
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
