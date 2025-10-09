<template lang="">
    <div class="card bg-base-200 shadow-sm h-[100vh]">
        <div class="card-body">
            <span class="badge badge-xs badge-info">Add new queue</span>
            <div class="grid md:grid-cols-2 sm:grid-cols-1">
                <div class="w-100">
                    <select class="select w-100" v-model="form.room_id">
                        <option disabled selected value="">Select room</option>
                        <option
                            v-for="(room, index) in rooms"
                            :key="index"
                            :value="room.id"
                            :disabled="room.active_queue"
                        >
                            {{ room.room_code }}
                        </option>
                    </select>
                    <fieldset class="fieldset w-100">
                        <legend class="fieldset-legend">Enter name</legend>
                        <input
                            type="text"
                            class="input w-100"
                            placeholder="Type here"
                            v-model="form.name"
                        />
                    </fieldset>
                    <div class="mt-6">
                        <button class="btn btn-primary" @click="handleSubmit">
                            Submit
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-pin-rows">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ROOM</th>
                                <th>WALK-INS</th>
                                <th>Arrival</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- row 1 -->
                            <tr v-for="(queue, index) in queues" :key="index">
                                <th>{{ index + 1 }}</th>
                                <td>
                                    {{
                                        rooms.find(
                                            (r) => r.id === queue.room_id
                                        ).room_code
                                    }}
                                </td>
                                <td>{{ queue.name }}</td>
                                <td>
                                    {{ queue.created_at }}
                                </td>
                                <td>
                                    <button
                                        class="btn btn-error btn-sm"
                                        type="button"
                                        @click="handleShowConfirmModal(queue)"
                                    >
                                        Set Inactive
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- modal -->
        <dialog id="confirmation-modal" class="modal">
            <div class="modal-box">
                <h3 class="text-lg font-bold">Attention!</h3>
                <p class="py-4">
                    You are about to set this queue to inactive. This action is
                    unreverssible.
                </p>
                <div class="modal-action">
                    <form method="dialog" class="grid grid-cols-2 gap-1">
                        <!-- if there is a button in form, it will close the modal -->
                        <button
                            class="btn btn-error"
                            @click="handleCancelSetInactive"
                        >
                            Cancel
                        </button>
                        <button
                            class="btn btn-warning"
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
