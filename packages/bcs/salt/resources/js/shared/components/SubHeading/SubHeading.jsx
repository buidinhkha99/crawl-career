export default function SubHeading({
    className,
    children,
    id,
    ...props
}) {
    return (
        <h3 className="subHeading" id={id} {...props}>
            {children}
        </h3>
    );
}
