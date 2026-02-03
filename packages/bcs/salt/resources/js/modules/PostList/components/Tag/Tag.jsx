import clsx from "clsx";

export default function Tag({ title, color, className }) {
    return (
        <div
            className={clsx("tag", className)}
            style={{ backgroundColor: color }}
        >
            <span className="tag--title">{title}</span>
        </div>
    );
}
