import "@/css/index.css";

import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/inertia-vue3";

import { Taskday } from "@/taskday";
import Layout from "@/layouts/AppLayout.vue";
import store from "@/store/index";

createInertiaApp({
  resolve: async (name) => {
    let [part1, part2] = name.split("/");

    if (part2 === undefined) {
      var page = (await import(`./views/${part1}.vue`)).default;
    } else {
      var page = (await import(`./views/${part1}/${part2}.vue`)).default;
    }

    if (page && !page.layout) {
      page.layout = Layout;
    }

    return page;
  },
  setup({ el, app, props, plugin }) {
    const instance = createApp({ render: () => h(app, props) });

    window.Taskday = window.taskday = new Taskday(instance);

    document.dispatchEvent(
      new CustomEvent("taskday:init", {
        detail: {},
        bubbles: true,
        composed: true,
        cancelable: true,
      })
    );

    instance
      .use(plugin)
      .use(store)
      .mixin({ methods: { route } })
      .mixin({
        methods: {
          taskday() {
            return window.taskday;
          },
          getTaskdayField(handle) {
            return window.taskday.fields[handle];
          },
        },
      })
      .component("Link", Link)
      .component("Head", Head)
      .mount(el);
  },
});
