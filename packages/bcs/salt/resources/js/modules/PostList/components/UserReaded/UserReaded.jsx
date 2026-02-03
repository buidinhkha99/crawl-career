export default function UserReaded({ img, name, desctiption }) {
    return (
        <div className="mb-5">
            <div className="flex items-center mb-[10px]">
                <img
                    src={img}
                    alt="img user readed"
                    width={25}
                    height={24}
                    className="mr-[9px] rounded-full"
                />
                <span className="text-[14px] font-normal text-black">
                    {name}
                </span>
            </div>
            <p className="text-[16px] text-[#808080] font-normal break-all">
                {desctiption}
            </p>
        </div>
    );
}
