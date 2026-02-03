import { a as jsxs, j as jsx } from "../ssr.mjs";
import { Menu, Layout } from "antd";
import { usePage } from "@inertiajs/react";
import "react/jsx-runtime";
import "react-dom/server";
import "process";
import "http";
const { Header: HeaderAntd } = Layout;
const Header = () => {
  usePage().url;
  const data = usePage().props.header;
  return /* @__PURE__ */ jsxs(HeaderAntd, { children: [
    /* @__PURE__ */ jsx("div", { className: "logo" }),
    /* @__PURE__ */ jsx(
      Menu,
      {
        theme: "dark",
        mode: "horizontal",
        defaultSelectedKeys: ["2"],
        items: data.menu.map((item, index) => {
          const key = index + 1;
          return {
            key,
            label: item.label
          };
        })
      }
    )
  ] });
};
export {
  Header as default
};
