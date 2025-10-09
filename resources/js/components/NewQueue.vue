<template lang="">
    <div class="card bg-base-200 shadow-sm overflow-x-auto">
        <div class="card-body">
            <span class="badge badge-xs badge-info">Add new queue</span>
            <div class="grid grid-cols-2 gap-2">
                <div class="w-100">
                    <select class="select w-100" v-model="form.room_id">
                        <option disabled selected value="">Select room</option>
                        <option
                            v-for="(room, index) in rooms"
                            :key="index"
                            :value="room.id"
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
                    <table class="table">
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
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    props: ["rooms"],
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
                console.log({ data });
                this.init();
                this.clearInputs();
            } catch (error) {
                console.error(error);
            }
        },
        async init() {
            try {
                const url = "/get-all-queues";
                const { data } = await axios.post(url);
                this.queues = data.data;
            } catch (error) {
                console.error(error);
            }
        },
        clearInputs() {
            this.form.name = "";
            this.form.room_id = "";
        },
    },
    computed: {
        getRooms() {
            return this.rooms;
        },
    },
};
</script>
<style lang=""></style>
