import { j as jsx } from "../ssr.mjs";
import "react/jsx-runtime";
import "react-dom/server";
import "@inertiajs/inertia-react";
import "process";
import "http";
const ByeWorld = () => {
  return /* @__PURE__ */ jsx("div", { children: "BYE WORLD!!!" });
};
export {
  ByeWorld as default
};
