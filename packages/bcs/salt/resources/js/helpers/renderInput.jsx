import { lazy } from "react";

const components = {
    text: lazy(() => import("../shared/components/TextBox/TextBox")),
    email: lazy(() => import("../shared/components/TextBox/TextBox")),
    password: lazy(() => import("../shared/components/TextBox/TextBox")),
    textarea: lazy(() => import("../shared/components/TextArea/TextArea")),
};

export const renderInput = (type, config) => {
    if (!type || type === "") return null;
    const SpecificComponent = components[type];
    return <SpecificComponent {...config} />;
};
