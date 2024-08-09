import { j as jsx } from "../ssr.mjs";
import { lazy, Fragment, Suspense } from "react";
import { usePage } from "@inertiajs/react";
import "react/jsx-runtime";
import "react-dom/server";
import "process";
import "http";
const Layouts = {
  "DefaultLayout": lazy(() => import("./DefaultLayout-b39e98c1.mjs"))
};
const components = {
  "Breadcrumb": lazy(() => import("./Breadcrumb-ad5470a9.mjs")),
  "Content": lazy(() => import("./Content-aceb016f.mjs")),
  "Footer": lazy(() => import("./Footer-21ae1c73.mjs")),
  "Header": lazy(() => import("./Header-94f62262.mjs")),
  "HelloWorld": lazy(() => import("./HelloWorld-d2b3c6a0.mjs")),
  "ByeWorld": lazy(() => import("./ByeWorld-bb435bd4.mjs"))
};
function loadComponent(name) {
  return components[name];
}
function renderComponents(components2) {
  return components2.map((Component, index) => {
    if (!Component) {
      return;
    }
    return /* @__PURE__ */ jsx(Fragment, { children: /* @__PURE__ */ jsx(Suspense, { fallback: /* @__PURE__ */ jsx("div", { children: "Loading Component..." }), children: /* @__PURE__ */ jsx(Component, {}) }) }, index);
  });
}
const Page = () => {
  const props = usePage().props;
  const Layout = Layouts[props.layout];
  const components2 = props.components.map((item) => loadComponent(item));
  return /* @__PURE__ */ jsx(Suspense, { fallback: /* @__PURE__ */ jsx("div", { children: "Loading..." }), children: /* @__PURE__ */ jsx(Layout, { className: "layout", children: renderComponents(components2) }) });
};
export {
  Page as default
};
