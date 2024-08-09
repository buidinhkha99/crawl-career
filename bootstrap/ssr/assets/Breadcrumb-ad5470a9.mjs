import { a as jsxs, j as jsx } from "../ssr.mjs";
import { Breadcrumb as Breadcrumb$1 } from "antd";
import "react/jsx-runtime";
import "react-dom/server";
import "@inertiajs/inertia-react";
import "process";
import "http";
const Breadcrumb = () => {
  return /* @__PURE__ */ jsxs(
    Breadcrumb$1,
    {
      style: {
        margin: "16px 0"
      },
      children: [
        /* @__PURE__ */ jsx(Breadcrumb$1.Item, { children: "Home" }),
        /* @__PURE__ */ jsx(Breadcrumb$1.Item, { children: "List" }),
        /* @__PURE__ */ jsx(Breadcrumb$1.Item, { children: "App" })
      ]
    }
  );
};
export {
  Breadcrumb as default
};
