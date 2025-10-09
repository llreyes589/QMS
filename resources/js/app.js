import "./bootstrap";

import { createApp } from "vue";
import QueueTable from "./components/QueueTable.vue";

const app = createApp();
app.component("queue-table", QueueTable);
app.mount("#app");
