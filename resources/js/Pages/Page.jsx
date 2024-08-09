import { React, Suspense } from "react";
import AppLayout from "@bcs/salt/Layouts/AppLayout";

import { usePage } from "@inertiajs/react";
import { loadComponent, renderComponents } from "@bcs/salt/RenderComponents";

const Page = () => {
    const props = usePage().props;
    const components = props.hasOwnProperty("components")
    ? props.components?.map((item, indexItem) => {
        const component = loadComponent(item?.type);
        const background = item.background;
        return { component, indexItem, background };
    })
    : null;

    return (
        <Suspense fallback={<div>Loading...</div>}>
            <AppLayout className="layout">
                {renderComponents(components)}
            </AppLayout>
        </Suspense>
    );
};

export default Page;
