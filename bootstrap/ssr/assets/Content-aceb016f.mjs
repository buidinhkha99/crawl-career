import { a as jsxs, j as jsx } from "../ssr.mjs";
import { theme, Layout } from "antd";
import Breadcrumb from "./Breadcrumb-ad5470a9.mjs";
import "react/jsx-runtime";
import "react-dom/server";
import "@inertiajs/inertia-react";
import "process";
import "http";
const { Content: ContentAntd } = Layout;
const Content = ({ children }) => {
  const {
    token: { colorBgContainer }
  } = theme.useToken();
  return /* @__PURE__ */ jsxs(
    ContentAntd,
    {
      style: {
        padding: "0 50px"
      },
      children: [
        /* @__PURE__ */ jsx(Breadcrumb, {}),
        /* @__PURE__ */ jsx(
          "div",
          {
            className: "site-layout-content",
            style: {
              background: colorBgContainer
            },
            children
          }
        )
      ]
    }
  );
};
export {
  Content as default
};
