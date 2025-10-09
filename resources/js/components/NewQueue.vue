<template lang="">
    <div class="card bg-base-200 shadow-sm overflow-x-auto">
        <div class="card-body">
            <span class="badge badge-xs badge-info">Add new queue</span>
            <fieldset class="fieldset">
                <select class="select" v-model="form.room_id">
                    <option disabled selected value="">Select room</option>
                    <option
                        v-for="(room, index) in rooms"
                        :key="index"
                        :value="room.id"
                    >
                        {{ room.room_code }}
                    </option>
                </select>
                <legend class="fieldset-legend">Enter name</legend>
                <input
                    type="text"
                    class="input"
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
