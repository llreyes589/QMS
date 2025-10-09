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
                                <th></th>
                                <th>Name</th>
                                <th>Job</th>
                                <th>Favorite Color</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- row 1 -->
                            <tr>
                                <th>1</th>
                                <td>Cy Ganderton</td>
                                <td>Quality Control Specialist</td>
                                <td>Blue</td>
                            </tr>
                            <!-- row 2 -->
                            <tr>
                                <th>2</th>
                                <td>Hart Hagerty</td>
                                <td>Desktop Support Technician</td>
                                <td>Purple</td>
                            </tr>
                            <!-- row 3 -->
                            <tr>
                                <th>3</th>
                                <td>Brice Swyre</td>
                                <td>Tax Accountant</td>
                                <td>Red</td>
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
    data() {
        return {
            form: {
                room_id: "",
                name: "",
            },
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
            } catch (error) {
                console.error(error);
            }
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
