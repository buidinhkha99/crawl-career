import { React, Suspense } from "react";
import AppLayout from "../../../Layouts/AppLayout";
import {Loading} from "../../../shared"

import { loadComponent, renderComponents } from "../../../RenderComponents";

export default function ResultSearch({ component }) {
    const components = component.map((item, indexItem) => {
        const component = loadComponent(item?.type);
        const background = item.background;
        return { component, indexItem, background };
    })
    return (
        <Suspense fallback={<Loading/>}>
            <AppLayout className="layout">
                {renderComponents(components)}
            </AppLayout>
        </Suspense>
    );
}
