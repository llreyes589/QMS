import "./bootstrap";

import { createApp } from "vue";
import QueueTable from "./components/QueueTable.vue";
import NewQueue from "./components/NewQueue.vue";

const app = createApp();
app.component("queue-table", NewQueue);
app.component("new-queue", NewQueue);
app.mount("#app");
