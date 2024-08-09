import HelloWorld from "@bcs/salt/RenderComponents/HelloWorld";

const CustomHelloWorld = () => {
    return (
        <div>
            <p className="font-normal hover:font-bold text-[#b43333]">CUSTOM HELLO WORLD!!!</p>
            <HelloWorld/>

            <hr/>
            <hr/>
        </div>
    );
}

export default CustomHelloWorld;
