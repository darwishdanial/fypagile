import "./bootstrap";

import { createInertiaApp } from "@inertiajs/react";
import { createRoot } from "react-dom/client";
import { CoordinatorLayout } from "@/Layouts/Coordinator/CoordinatorLayout";
import { PanelLayout } from "@/Layouts/Panel/PanelLayout";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.tsx", { eager: true });
        let page = pages[`./Pages/${name}.tsx`];
        if (name.startsWith("Coordinator/")) {
            page.default.layout =
                page.default.layout ||
                ((page) => <CoordinatorLayout>{page}</CoordinatorLayout>);
        } else if (name.startsWith("Panel/")) {
            page.default.layout =
                page.default.layout ||
                ((page) => <PanelLayout>{page}</PanelLayout>);
        }
        return page;
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});
