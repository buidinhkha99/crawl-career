import { a as jsxs, j as jsx } from "../ssr.mjs";
import "react";
import { Layout } from "antd";
import Header from "./Header-94f62262.mjs";
import Footer from "./Footer-21ae1c73.mjs";
import Content from "./Content-aceb016f.mjs";
import "react/jsx-runtime";
import "react-dom/server";
import "@inertiajs/inertia-react";
import "process";
import "http";
import "./Breadcrumb-ad5470a9.mjs";
const DefaultLayout = ({ children }) => {
  return /* @__PURE__ */ jsxs(Layout, { className: "layout", children: [
    /* @__PURE__ */ jsx(Header, {}),
    /* @__PURE__ */ jsx(
      Content,
      {
        style: {
          padding: "0 50px"
        },
        children
      }
    ),
    /* @__PURE__ */ jsx(Footer, {})
  ] });
};
export {
  DefaultLayout as default
};
