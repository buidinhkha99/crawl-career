import clsx from "clsx";

export default function Logo({ logo, alt="" }) {
    const layoutKey = logo.layout.split("-");
    const direction =
        layoutKey[layoutKey.length - 1] !== "horizontal" ? "col" : "row";

    const renderPosition = () => {
        if (direction === "col") return "flex flex-col items-center";
        return "flex justify-center items-center";
    };

    const renderOrder = () => {
        if (layoutKey[1] !== "image") return `flex-${direction}-reverse`;
    };
    const renderMargin = () => {
        if (layoutKey[layoutKey.length - 1] === "horizontal")
            return "mx-[10px]";
        return "mb-[10px]";
    };

  return (
    <a href={logo.url} className={clsx(renderPosition(), renderOrder())}>
      {logo.img && layoutKey.includes("image") && (
        <img
          src={logo.img}
          alt={alt || "logo"}
          className={clsx(
            renderMargin(),
            "img--logo"
          )}
        />
      )}
      {logo.text && layoutKey.includes("text") && (
        <span
          style={{
            color: logo.color || "#FEF9EF",
            fontFamily: logo.font || "Roboto",
          }}
          className="text--logo"
        >
          {logo.text}
        </span>
      )}
    </a>
  );
}
