import { Modal } from "antd";
import clsx from "clsx";

export default function ModalNotification({
    open,
    handleCancel,
    handleAgree,
    dataModal,
    className,
}) {
    return (
        <Modal
            closable={false}
            fontsize="32px"
            centered
            className="customModalVimico"
            open={open}
            footer={null}
            width={700}
            bodyStyle={{
                backgroundColor: "#D6D9E4",
                padding: "80px",
                color: "white",
                borderRadius: "20px",
            }}
        >
            <div className="flex flex-col gap-[60px]">
                {dataModal.title && (
                    <p className="flex flex-row justify-center text-black font-bold text-[24px]">
                        {dataModal.title}
                    </p>
                )}
                <div className={clsx(className, "gap-[33px]")}>
                    {dataModal.cancel && (
                        <button
                            className="bg-[#586BA4] text-white py-3 px-6 text-base font-bold uppercase rounded-[24px] hover:bg-[#324376]"
                            onClick={() => handleCancel()}
                        >
                            {dataModal.cancel}
                        </button>
                    )}
                    {dataModal.agree && (
                        <button
                            className="bg-[#586BA4] text-white py-3 px-6 text-base font-bold uppercase rounded-[24px] hover:bg-[#324376]"
                            onClick={() => {
                                handleAgree();
                                handleCancel();
                                localStorage.removeItem("appDesktop");
                            }}
                        >
                            {dataModal.agree}
                        </button>
                    )}
                </div>
            </div>
        </Modal>
    );
}
